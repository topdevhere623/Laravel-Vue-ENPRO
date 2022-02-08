<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// для импорта с Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TowerconstructionaccessoryImport;
use App\Imports\TowerconstructionbaseImport;
use App\Imports\TowerconstructionbasicImport;
use App\Imports\TowerconstructioninsulatorImport;
use App\Imports\TowerconstructionmetalImport;
use App\Imports\TowerconstructionstandartImport;
use App\Imports\TowerconstructionwoodImport;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Tower;
use App\Models\Towerinfo;
use App\Models\Towerconstructionpivot;
use App\Models\Towerconstructionaggregatepivot;
use App\Models\Towerconstructionrealpivot;

// контроллер модели
class TowerConstructionMasterController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // ------------------------------------------------------------------
    // справочник (список)
    public function index()
    {
        // открыть вьюшку
        return view('backend.towerconstructionmaster.index');
    }

    // ------------------------------------------------------------------
    // загрузка справочника (Vue)
    public function vueSpravLoad(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getSpravName = ucfirst(trim($request['spravName'])); // имя справочника
        $getFilterName = trim($request['filterName']); // поисковое выражение
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($getSpravName);

        // запрос
        $myReturn = $model
            ::when(isset($getFilterName) and $getFilterName !== '', function ($query) use ($getFilterName) {
                return $query->
                where('name', 'like', '%' . $getFilterName . '%')
                    ->orWhere('series', 'like', '%' . $getFilterName . '%')
                    ->orWhere('mark', 'like', '%' . $getFilterName . '%')
                    ->orWhere('album', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // поиск по всем справочникам (Vue)
    public function vueSpravlGlobalSearch(Request $request)
    {
        // переданные параметры через запрос post
        $getSpravNames = json_decode($request['spravNames'], true); // поисковое выражение
        $getFilterName = trim($request['filterName']); // поисковое выражение

        $mySearchReturn = collect();
        if (isset($getFilterName) and $getFilterName !== '')
            // в цикле собрать информацию со всех таблиц
            foreach ($getSpravNames as $mySearchTable) {

                if (isset($mySearchTable['globalSearch']) and $mySearchTable['globalSearch']) {

                    // тело запроса
                    $query = "SELECT id, name, series, mark, album, " .
                        "'" . $mySearchTable['ru'] . "' AS spravRu, " .
                        "'" . $mySearchTable['name'] . "' AS spravName " .
                        "FROM `" . strtolower($mySearchTable['name']) . "` " .
                        "WHERE " .
                        "(name LIKE '%" . $getFilterName . "%') OR " .
                        "(series LIKE '%" . $getFilterName . "%') OR " .
                        "(mark LIKE '%" . $getFilterName . "%') OR " .
                        "(album LIKE '%" . $getFilterName . "%')";
                    //return $query;
                    $myReturn = DB::select($query);
                    // обьедить результаты поиска
                    $mySearchReturn = $mySearchReturn->merge($myReturn);
                }
            }

        // возвращаемый параметр
        return $mySearchReturn;
    }

    // ------------------------------------------------------------------
    // сохранение строки справочника (Vue)
    public function vueSpravSave(Request $request)
    {

        // переданные параметры через запрос post
        $getSpravName = ucfirst(trim($request['spravName'])); // имя справочника
        $getSpravRowContent = json_decode($request['spravRowContent'], true); // содержимое строки в справочнике

        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($getSpravName);

        // сохранение строки
        if (isset($getSpravRowContent['id']) and $getSpravRowContent['id'] > 0) { // !!! будет символьным 'null', если его не было
            $modelSave = $model::find($getSpravRowContent['id']);
        } else {
            $modelSave = new $model;
        }

        // проверка, есть ли данные в полученном массиве
        $myFields = ['album', 'name', 'mark', 'series', 'weight', 'img'];
        foreach ($myFields as $item) {
            if (isset($getSpravRowContent[$item])) {
                $modelSave->$item = $getSpravRowContent[$item];
            }
        }

        // сохранить
        $modelSave->save();
        // присвоенный ID, если его еще не было
        $newID = $modelSave->id;
        // заново прочитать всю строку (с новым id, статусом, датой обновления и пр.)
        $modelSave = $modelSave::find($newID);

        // возвращаемый параметр
        return $modelSave;
    }

    // ------------------------------------------------------------------
    // удаление строки справочника (Vue) коммент новый
    public function vueSpravDelete(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = ucfirst(trim($request['spravName'])); // имя справочника
        $getModelID = $request['spravRowID']; // ID строки в справочнике (!!! будет символьным 'null', если его не было)

        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($getModelName);

        // удаление строки
        if ($getModelID !== 'null') {
            // из базы
            $return = $model::where('id', $getModelID)->delete();
        }

        // возвращаемый параметр
        return $return;
    }

    // ------------------------------------------------------------------
    // загрузка сводной (Vue) - для марок опор, сборных агрегатов и реальных опор
    public function vuePivotLoad(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = $request['modelName'];
        $getModelID = $request['modelID'];
        $getSpravName = $request['spravName'];

        if (isset($getModelName) and isset($getSpravName)) {

            // получение имени сводной таблицы pivot
            $myReturn = self::getModelPivotName($getModelName);
            $modelPivotName = $myReturn['modelPivotName'];
            $modelPivotFieldName = $myReturn['modelPivotFieldName'];

            // запрос
            if ($getModelName === 'Tower' and $getSpravName === 'Towerinfo') {
                // это реальная опора, сборные данные из справочника марок опор
                $return = $modelPivotName
                    ::with('towerconstructionpivot')
                    ->where($modelPivotFieldName, $getModelID)
                    ->where('mark', 1)
                    ->get();
            } else {
                // имя модели полный путь
                $getSpravName = "App\\" . (Str::contains($getSpravName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($getSpravName);

                // это марка опоры или агрегат
                $return = $modelPivotName
                    ::with('towerconstructionpivot')
                    ->where($modelPivotFieldName, $getModelID)
                    ->where('towerconstructionpivot_type', $getSpravName)
                    ->get();
            }

            // возвращаемый параметр
            return $return;
        }
    }

    // ------------------------------------------------------------------
    // сохранение сводной (Vue) - для марок опор, сборных агрегатов и реальных опор
    public function vuePivotSave(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = $request['modelName'];
        $getModelID = $request['modelID'];
        $getSpravName = $request['spravName'];
        $getSpravData = json_decode($request['spravData'], true);
        $getSpravID = $request['spravID'];

        // получение имени сводной таблицы pivot
        $myReturn = self::getModelPivotName($getModelName);
        $modelPivotName = $myReturn['modelPivotName'];
        $modelPivotFieldName = $myReturn['modelPivotFieldName'];

        // имя модели полный путь
        $getSpravFullName = "App\\" . (Str::contains($getSpravName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($getSpravName);

        // накопительный массив для удаления ненужных строк из pivot
        $myForDelete = [];

        if (isset($getModelName) and isset($getSpravName) and isset($getSpravData)) {
            if (count($getSpravData) > 0) {
                foreach ($getSpravData as $item) {

                    // тип родителя (класс)
                    $myTowerconstructionpivotType = (isset($item['towerconstructionpivot_type']) && $item['towerconstructionpivot_type'] !== '') ? $item['towerconstructionpivot_type'] : $getSpravFullName;

                    // поиск в сводной таблице pivot
                    $towerConstructionPivots = $modelPivotName
                        ::where($modelPivotFieldName, $getModelID)
                        ->where('towerconstructionpivot_type', $myTowerconstructionpivotType)
                        ->where('towerconstructionpivot_id', $item['id'])
                        ->get()->first();

                    if (!$towerConstructionPivots) {
                        $towerConstructionPivots = new $modelPivotName();
                    }

                    // данные для сохранения в сводной таблице
                    $towerConstructionPivots->$modelPivotFieldName = $getModelID;
                    $towerConstructionPivots->towerconstructionpivot_type = $myTowerconstructionpivotType;
                    $towerConstructionPivots->towerconstructionpivot_id = $item['id'];
                    $towerConstructionPivots->kol = $item['kol'];
                    if ($getModelName === 'Tower' and $getSpravName === 'Towerinfo') {
                        $towerConstructionPivots->mark = 1;
                    }
                    // сохранить
                    $towerConstructionPivots->save();

                    // накопительный массив для удаления ненужных строк из pivot
                    $myForDelete [] = $towerConstructionPivots->id; //$item['id'];
                }
            }

            // удалить строчки из pivots, которые стали неактуальные
            $modelPivotName
                ::where($modelPivotFieldName, $getModelID)
                ->where('towerconstructionpivot_type', $getSpravFullName)
                ->when(count($myForDelete) > 0, function ($query) use ($myForDelete) {
                    return $query->whereNOTIn('id', $myForDelete); // ->whereNOTIn('towerconstructionpivot_id', $myForDelete)
                })
                ->delete();
        }
    }

    // ------------------------------------------------------------------
    // сохранить данные из марки опоры в реальную опору (Vue)
    public function vuePivotSaveAsMark($getTowerinfoID, $getTowerID)
    {
        // прочитать у марки опоры
        $towerinfo = Towerinfo::with('towerconstructions')->where('id', $getTowerinfoID)->get()->first();

        if (isset($towerinfo->towerconstructions) and count($towerinfo->towerconstructions) > 0) {
            {
                // удалить старые данные
                Towerconstructionrealpivot::
                where('tower_id', $getTowerID)->
                where('mark', 1)->
                delete();

                // записать новые данные
                foreach ($towerinfo->towerconstructions as $item) {
                    $modelPivotSave = new Towerconstructionrealpivot();
                    $modelPivotSave->tower_id = $getTowerID;
                    $modelPivotSave->towerconstructionpivot_type = $item['towerconstructionpivot_type'];
                    $modelPivotSave->towerconstructionpivot_id = $item['towerconstructionpivot_id'];
                    $modelPivotSave->kol = $item['kol'];
                    $modelPivotSave->mark = 1;
                    $modelPivotSave->save();
                }
            }
        }
    }

    // ------------------------------------------------------------------
    // скопировать из марки (Vue)
    public function vueCopyFromTowerinfo(Request $request)
    {
        // переданные параметры через запрос post
        $getModelID = $request['modelID'];

        // получить полную информацию по переданной опоре
        $tower = Tower::find($getModelID);

        if ($tower) {

            // марка опоры
            $myTowerinfoID = $tower->towerinfo_id;

            // получение имени сводной таблицы pivot
            $myReturn = self::getModelPivotName('Towerinfo');
            $modelPivotName = $myReturn['modelPivotName'];
            $modelPivotFieldName = $myReturn['modelPivotFieldName'];

            // прочитать у марки опоры
            $return = $modelPivotName
                ::with('towerconstructionpivot')
                ->where($modelPivotFieldName, $myTowerinfoID)
                ->get();

            // возвращаемый параметр
            return $return;
        }
    }

    // ------------------------------------------------------------------
    // итоговый рассчетный вес всех компонентов (Vue)
    public function vueWeightItogo(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = $request['modelName'];
        $getModelID = $request['modelID'];

        if (isset($getModelName) and isset($getModelID)) {

            // получение имени сводной таблицы pivot
            $myReturn = self::getModelPivotName($getModelName);
            $modelPivotName = $myReturn['modelPivotName'];
            $modelPivotFieldName = $myReturn['modelPivotFieldName'];

            // получить все записи для данной марки опоры, обьединив со справочниками компонент
            $modelPivot = $modelPivotName::with('towerconstructionpivot')
                ->where($modelPivotFieldName, $getModelID)
                ->orderBy('towerconstructionpivot_type', 'asc')
                ->get();

            // просканировать этот список
            $contentWeightItogo = 0;
            if ($modelPivot and count($modelPivot) > 0) {
                foreach ($modelPivot as $item) {
                    if (isset($item['towerconstructionpivot']) and isset($item['towerconstructionpivot']['weight'])) {
                        $contentWeightItogo += (float)$item['kol'] * (float)$item['towerconstructionpivot']['weight'];
                    }
                }
            }
            // округлить значения
            $contentWeightItogo = round($contentWeightItogo, 6);

            // возвращаемый параметр
            $myReturn = [
                'weightItogo' => $contentWeightItogo,
                'modelData' => $modelPivot,
            ];

            // возвращаемый параметр
            return $myReturn;
        }
    }

    // ------------------------------------------------------------------
    // получение имени сводной таблицы pivot
    public function getModelPivotName($getModelName)
    {
        switch ($getModelName) {
            case 'Towerinfo':
                $modelPivotName = 'Towerconstructionpivot';
                $modelPivotFieldName = 'towerinfo_id';
                break;
            case 'Towerconstructionaggregate':
                $modelPivotName = 'Towerconstructionaggregatepivot';
                $modelPivotFieldName = 'towerconstructionaggregate_id';
                break;
            case 'Tower':
                $modelPivotName = 'Towerconstructionrealpivot';
                $modelPivotFieldName = 'tower_id';
                break;
        }

        // имя модели полный путь
        $modelPivotName = "App\\" . (Str::contains($modelPivotName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($modelPivotName);

        // возвращаемый параметр
        $myReturn = [
            'modelPivotName' => $modelPivotName,
            'modelPivotFieldName' => $modelPivotFieldName,
        ];

        // возвращаемый параметр
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // импорт/экспорт (Vue)
    public function vueImportExport(Request $request)
    {
        // переданные параметры через запрос post
        $getRegim = $request['regim'];
        $getSpravName = $request['spravName'];
        $getFile = $request['file'];

        // полный путь модели именно для импорта
        $getSpravName = "App\\Imports\\" . ucfirst($getSpravName) . 'Import';

        switch ($getRegim) {
            case 'import':
                // импорт
                Excel::import(new $getSpravName, $getFile);
                break;
            case 'export':
                // экспорт
                $myFile = Excel::download(new TowerinfoExport, 'towerinfo.xlsx');

//                        return response()->json("data:application/vnd.ms-excel;base64," . base64_encode($myFile));
//                        //return $myFile;

                $response = array(
                    'name' => 'towerinfo',
                    'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($myFile)
                );
                return response()->json($response);

                //self::vueExport();
                break;
        }
    }
}
