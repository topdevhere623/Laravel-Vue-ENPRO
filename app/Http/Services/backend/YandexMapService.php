<?php

namespace App\Http\Services\backend;

use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\ModelService;

// мои модели
use App\Models\Identifiedobject;
use App\Models\Span;
use App\Models\Substation;
use App\Models\Tower;

// сервис по картам Яндекса
class YandexMapService
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    // получить список ближайших обьектов по дистанции
    public function getNearObjectsOnDistance($getTableName, $getCurrentCoords, $getKolStrok = 50)
    {
        // проверка, что координаты не null
        if (is_null($getCurrentCoords)) return null; // досрочный выход

        // имя таблицы
        $tableName = strtolower($getTableName);

        // вычисляемое поле дистанции в запросе
        $distCalc = "(((acos(sin((" . $getCurrentCoords[0] . " * pi() / 180)) * sin((identifiedobject.lat * pi() / 180)) + cos((" . $getCurrentCoords[0] . " * pi() / 180)) * cos((identifiedobject.lat * pi() / 180)) * cos(((" . $getCurrentCoords[1] . " - identifiedobject.long) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515) AS dist";
        // сырой запрос Laravel - отсортированный по дистанции список обьектов - первые N
        $return = DB::table($tableName)
            ->select($tableName . '.id', 'identifiedobject.name')
            ->selectRaw($distCalc)
            ->leftJoin('identifiedobject', $tableName . '.identifiedobject_id', '=', 'identifiedobject.id')
            ->where($tableName . '.deleted_at', null)
            ->where('identifiedobject.deleted_at', null)
            ->where('identifiedobject.lat', '<>', null)
            ->orderBy('dist')
            ->take($getKolStrok)
            ->get();

        // возвращаемый параметр
        return $return;
    }

    // получить список ближайших обьектов в видимой области карты
    public function getNearObjects($getBounds, $getIOIDsMinus = null)
    {
        // проверка, что координаты не null
        if (is_null($getBounds)) return null; // досрочный выход

        // координаты
        $myMinLat = $getBounds[0][0];
        $myMinLong = $getBounds[0][1];
        $myMaxLat = $getBounds[1][0];
        $myMaxLong = $getBounds[1][1];

        // ТП (реальное, не фиктивное)
        $substations = null;
//        $substations = Substation
//            ::selectRaw('substation.id, substation.identifiedobject_id, a.name, a.lat, a.long')
//            ->leftJoin('identifiedobject as a', 'substation.identifiedobject_id', '=', 'a.id')
//            ->whereBetween('a.lat', [$myMinLat, $myMaxLat])
//            ->whereBetween('a.long', [$myMinLong, $myMaxLong])
//            ->whereNull('substation.deleted_at')
//            ->whereNull('a.deleted_at')
//            ->distinct()
//            ->get();

        // опоры
        $towers = Tower::whereHas('identifiedobject', function ($query) use ($myMinLat, $myMaxLat, $myMinLong, $myMaxLong) {
            $query->whereBetween('lat', [$myMinLat, $myMaxLat])->whereBetween('long', [$myMinLong, $myMaxLong]);
        })
            ->when(!is_null($getIOIDsMinus), function ($query) use ($getIOIDsMinus) {
                return $query->whereNOTIn('id', $getIOIDsMinus['towers']);
            })
            ->get();

        if (false) {
            $towers = Tower
                ::selectRaw('tower.*')
                ->join('identifiedobject as i', 'tower.identifiedobject_id', '=', 'i.id')
                ->whereBetween('i.lat', [$myMinLat, $myMaxLat])
                ->whereBetween('i.long', [$myMinLong, $myMaxLong])
                ->whereNull('tower.deleted_at')
                ->whereNull('i.deleted_at')
                ->when(!is_null($getIOIDsMinus), function ($query) use ($getIOIDsMinus) {
                    return $query->whereNOTIn('tower.id', $getIOIDsMinus['towers']);
                })
                ->get();
        }

        // пролеты 701
        $spans = Span
            ::selectRaw('span.*,
            i.name as span_name, i.voltage_id,
            i1.localname as name_start, i1.lat as lat_start, i1.long as long_start,
            i2.localname as name_end, i2.lat as lat_end, i2.long as long_end,
            a.id as acline_id,
            ia.name as name_acline')
            ->join('identifiedobject as i', 'span.identifiedobject_id', '=', 'i.id')
            ->join('identifiedobject as i1', 'span.startIO_id', '=', 'i1.id')
            ->join('identifiedobject as i2', 'span.endIO_id', '=', 'i2.id')
            ->join('aclinesegment as s', 'span.aclinesegment_id', '=', 's.id')
            ->join('acline as a', 's.acline_id', '=', 'a.id')
            ->join('identifiedobject as ia', 'a.identifiedobject_id', '=', 'ia.id')
            ->where('span.spantype', 701)
            ->whereBetween('i1.lat', [$myMinLat, $myMaxLat])
            ->whereBetween('i1.long', [$myMinLong, $myMaxLong])
            ->whereBetween('i2.lat', [$myMinLat, $myMaxLat])
            ->whereBetween('i2.long', [$myMinLong, $myMaxLong])
            ->when(!is_null($getIOIDsMinus), function ($query) use ($getIOIDsMinus) {
                return $query->whereNOTIn('span.id', $getIOIDsMinus['spans']);
            })
            ->whereNull('span.deleted_at')
            ->whereNull('i.deleted_at')
            ->whereNull('i1.deleted_at')
            ->whereNull('i2.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('a.deleted_at')
            ->whereNull('ia.deleted_at')
            ->distinct()
            ->get();

        // возвращаемый параметр
        $myReturn = [
            'substations' => $substations,
            'towers' => $towers,
            'spans' => $spans,
        ];
        return $myReturn;
    }

    // список точек для карты
    public function getMapContent($modelName = 'identifiedobject', $needRender = false)
    {
        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($modelName);

        if ($modelName == 'identifiedobject') {
            // контент
            $data = $model::all();
            // это IO
            // заголовок
            $mapTitle = 'Все имеющиеся обьекты';
            // роут для кнопки New
            $mapRoutNew = '';
        } else {
            // контент
            $data = $model::with('identifiedobject')->get();
            // это конкретная модель - даные через IO
            // заголовок - название модели
            $mapTitle = ($model)::title2;
            // роут для кнопки New
            $mapRoutNew = $modelName . '.edit';
        }

        $mapContent = '';
        if (count($data) > 0) {
            $mapContent = [];
            foreach ($data as $item) {

                if ($modelName == 'identifiedobject') {
                    // это IO
                    $lat = $item->lat;
                    $long = $item->long;
                    $name = $item->name;
                } else {
                    // это конкретная модель - даные через IO
                    $lat = $item->identifiedobject->lat;
                    $long = $item->identifiedobject->long;
                    $name = $item->identifiedobject->name;
                }

                // проверка, чтобы были указаны координаты
                if (!empty($lat) and !empty($long)) {
                    // координаты есть - включить в список
                    $mapContent [] = [$lat, $long, $name];
                }
            }
        }

        // возвращаемый параметр
        if ($needRender == true) {
            // вернуть рендер страницы
            $html = view('backend.yandex_map.indexcontent')->with(
                [
                    'mapContent' => $mapContent,
                    'mapTitle' => $mapTitle,
                    'mapRoutNew' => $mapRoutNew,
                ])->render();
            return response()->json(['html' => $html]);
        } else {
            // вернуть данные для карты
            return
                [
                    'mapContent' => $mapContent,
                    'mapTitle' => $mapTitle,
                    'mapRoutNew' => $mapRoutNew,
                ];
        }
    }

    // вычисление расстояния между двумя точками (дистанция)
    public function getDistanceBetweenPoints(
        $lat1,
        $long1,
        $lat2,
        $long2,
        $unit = 'M')
    {
        // если начало и конеч совпадают, то NAN возвращалось
        if ($lat1 == $lat2 and $long1 == $long2) return 0;

        // преобразовать полученные параметры в цифровые значения, если вдруг передали в символах
        if (gettype($lat1) == 'string') $lat1 = (float)$lat1;
        if (gettype($long1) == 'string') $long1 = (float)$long1;
        if (gettype($lat2) == 'string') $lat2 = (float)$lat2;
        if (gettype($long2) == 'string') $long2 = (float)$long2;

        $theta = $long1 - $long2;
        $distance = (sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch ($unit) {
            case 'Mi':
                break;
            case 'Km' :
                $distance = $distance * 1.609344;
                break;
            case 'M' :
                $distance = $distance * 1.609344 * 1000;
                break;
        }
        return round($distance, 2);
    }
}
