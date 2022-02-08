<?php

namespace App\Http\Controllers\backend\Acline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use File;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Http\Services\backend\YandexMapService;

// модель
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Aclinesegmentinfo;
use App\Models\AclineStatus;
use App\Models\BaseVoltage;
use App\Models\Busbarsection;
use App\Models\Connectivitycode;
use App\Models\Crossing;
use App\Models\Crossingtype;
use App\Models\Customer;
use App\Models\Discharger;
use App\Models\Dischargerinfo;
use App\Models\Disconnector;
use App\Models\DisconnectorInfo;
use App\Models\Endpoint;
use App\Models\Identifiedobject;
use App\Models\Layingconditionkind;
use App\Models\Materialkind;
use App\Models\Span;
use App\Models\Substation;
use App\Models\Terminal;
use App\Models\Tower;
use App\Models\Towerkind;
use App\Models\Towerinfo;
use App\Models\Towerconstructionkind;
use App\Models\Towermaterial;

// контроллер модели (унаследую)
class AclineRepairController extends AclineController
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService, YandexMapService $yandexMapService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
        $this->yandexMapService = $yandexMapService;
    }

    // ------------------------------------------------------------------
    // открытие вьюшки - целостность данных
    public function repair()
    {
        // содержимое загрузить позже Vue

        // открыть вюшку
        return view('backend.acline.repair');
    }

    // ------------------------------------------------------------------
    // целостность данных - модуль, который выбрал Пользователь
    public function vueRepair(Request $request)
    {
        // переданные параметры через запрос post
        $getMode = $request['mode']; // модуль
        $getRegim = $request['regim']; // режим

        switch ($getMode) {
            case 'SpanLenght':
                // целостность данных - корректировка длин всех пролетов ЛЭП
                $myComment = self::vueRepairSpanLenght($getRegim);
                break;
            case 'FindLost':
                // целостность данных - очистка от несвязанных ни с какой линией ЛЭП строк
                $myComment = self::vueRepairFindLost($getRegim);
                break;
            case 'TowerInDoubleAcline':
                // целостность данных - опоры, участвующие в совместном подвесе
                $myComment = self::vueRepairTowerInDoubleAcline($getRegim);
                break;
            case 'Images':
                // целостность данных - прикрепленные изображения
                $myComment = self::vueRepairImages($getRegim);
                break;
            case 'DeletedAt':
                // целостность данных - сжатие базы за счет удаления помеченных на удаление
                $myComment = self::vueRepairDeletedAt($getRegim);
                break;
        }

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // целостность данных - корректировка длин всех пролетов ЛЭП
    public function vueRepairSpanLenght($getRegim)
    {
        // статистика
        $mySpanCount = 0;
        $mySpanCount701 = 0;
        $mySpanCount702 = 0;
        $mySpanLenght_1 = 0;
        $mySpanLenght_2 = 0;
        $mySpanLenght701_1 = 0;
        $mySpanLenght701_2 = 0;
        $mySpanLenght702_1 = 0;
        $mySpanLenght702_2 = 0;
        $myNoRecalc701 = 0;
        $myNoRecalc702 = 0;
        $myUpdate701 = 0;
        $myUpdate702 = 0;
        $myText = '';

        // все пролеты
        $spans = Span::with('identifiedobject', 'startIO', 'endIO')->get(); // ->where('id', $myDebugSpanID)
        if ($spans) {
            foreach ($spans as $span) {

                // текущий пролет
                $currentSpanID = $span->id;
                $currentSpanName = $span->identifiedobject->name;
                $currentSpanType = $span->spantype;
                $currentSpanLenght = $span->spanlength;
                $currentPoints = json_decode($span->points, true);
                $currentStartLat = $span->startIO->lat;
                $currentStartLong = $span->startIO->long;
                $currentEndLat = $span->endIO->lat;
                $currentEndLong = $span->endIO->long;

                // статистика
                $mySpanCount++;
                $mySpanLenght_1 += $currentSpanLenght;

                if ($currentSpanType == 701) {
                    // воздушный пролет - считать по прямой

                    // статистика
                    $mySpanCount701++;
                    $mySpanLenght701_1 += $currentSpanLenght;

                    if ($currentStartLat != 0 and $currentStartLong != 0 and $currentEndLat != 0 and $currentEndLong != 0) {
                        // координаты есть

                        // вычисление расстояния между двумя точками (дистанция)
                        $newSpanLenght = $this->yandexMapService->getDistanceBetweenPoints($currentStartLat, $currentStartLong, $currentEndLat, $currentEndLong);

                        // статистика
                        $mySpanLenght_2 += $newSpanLenght;
                        $mySpanLenght701_2 += $newSpanLenght;

                        // обновить базу
                        if ($getRegim == 'update' and number_format($currentSpanLenght, 4) != number_format($newSpanLenght, 4)) {
                            $myUpdate701 += self::vueRepairSpanLenght_Update($currentSpanID, ['spanlength' => $newSpanLenght]);
                        }

                    } else {
                        // статистика
                        $myNoRecalc701++;
                        $mySpanLenght_2 += $currentSpanLenght;
                        $mySpanLenght701_2 += $currentSpanLenght;
                    }
                }
                if ($currentSpanType == 702) {
                    // кабельынй пролет - считать и по характерным точкам, если они есть

                    // статистика
                    $mySpanCount702++;
                    $mySpanLenght702_1 += $currentSpanLenght;

                    if ($currentStartLat != 0 and $currentStartLong != 0 and $currentEndLat != 0 and $currentEndLong != 0) {
                        // координаты есть

                        if ($currentPoints != null and count($currentPoints) > 0) {
                            // характерные точки есть - пройтись по каждой паре

                            // проверить, есть ли в этом массиве start и end
                            $myPointsKol = count($currentPoints);
                            $myPointFirst = $currentPoints[0];
                            $myPointLast = $currentPoints[$myPointsKol - 1];
                            if (round($currentStartLat, 12) <> round($myPointFirst[0], 12) and round($currentStartLong, 12) <> round($myPointFirst[1], 12)) {
                                // начала нет - добавить
                                array_unshift($currentPoints, [$currentStartLat, $currentStartLong]);
                            }
                            if (round($currentEndLat, 12) != round($myPointLast[0], 12) and round($currentEndLong, 12) != round($myPointLast[1], 12)) {
                                // конца нет - добавить
                                array_push($currentPoints, [$currentEndLat, $currentEndLong]);
                            }

                            // повторно подсчитать кол-во точек
                            $myPointsKol = count($currentPoints);
                            // сканировать все характерные точки
                            $newSpanLenght = 0;

                            for ($i = 0; $i < $myPointsKol - 1; $i = $i + 1) { // -1
                                // вычисление расстояния между двумя точками (дистанция)
                                // здесь накопительное
                                $newSpanLenght += $this->yandexMapService->getDistanceBetweenPoints($currentPoints[$i][0], $currentPoints[$i][1], $currentPoints[$i + 1][0], $currentPoints[$i + 1][1]);
                            }

                        } else {
                            // характерных точек нет - считать от start до end

                            // вычисление расстояния между двумя точками (дистанция)
                            $newSpanLenght = $this->yandexMapService->getDistanceBetweenPoints($currentStartLat, $currentStartLong, $currentEndLat, $currentEndLong);
                        }

                        // статистика
                        $mySpanLenght_2 += $newSpanLenght;
                        $mySpanLenght702_2 += $newSpanLenght;


                        // текст для статистики
                        if (number_format($currentSpanLenght, 4) <> number_format($newSpanLenght, 4)) {
                            $myText .= '<br>Пролет ID:' . $currentSpanID . ($currentSpanName == '' ? '' : ' (' . $currentSpanName . ')');
                            $myText .= '<br>Длина до: ' . $currentSpanLenght;
                            $myText .= '<br>Длина после: ' . $newSpanLenght . '<br>';
                        }

                        // обновить базу
                        if ($getRegim == 'update' and number_format($currentSpanLenght, 4) != number_format($newSpanLenght, 4)) {
                            $myUpdate702 += self::vueRepairSpanLenght_Update($currentSpanID, ['spanlength' => $newSpanLenght]);
                        }

                    } else {
                        // статистика
                        $myNoRecalc702++;
                        $mySpanLenght_2 += $currentSpanLenght;
                        $mySpanLenght702_2 += $currentSpanLenght;
                    }
                }
            }
        }

        // возвращаемый параметр
        $myComment = 'СТАТИСТИКА' .
            '<br><br>Обнаружено пролетов: ' . $mySpanCount .
            ($mySpanCount701 > 0 ? '<br> - воздушных: ' . $mySpanCount701 . ';' : '') .
            ($mySpanCount702 > 0 ? '<br> - кабельных' . $mySpanCount702 . ';' : '') .
            ($myNoRecalc701 + $myNoRecalc702 > 0 ? '<br><br>Из-за отсутствия координат вершин было невозможно рассчитать длину для:' : '') .
            ($myNoRecalc701 > 0 ? '<br> - воздушных пролетов: ' . $myNoRecalc701 . ';' : '') .
            ($myNoRecalc702 > 0 ? '<br> - кабельных пролетов: ' . $myNoRecalc702 . ';' : '') .
            ($myUpdate701 + $myUpdate702 > 0 ? '<br><br>Обновлено строк в базе данных у:' : '') .
            ($myUpdate701 > 0 ? '<br> - воздушных пролетов: ' . $myUpdate701 . ';' : '') .
            ($myUpdate702 > 0 ? '<br> - кабельных пролетов: ' . $myUpdate702 . ';' : '') .
            '<br><br>Общая длина всех пролетов:' .
            '<br> - до внесения правок: ' . round($mySpanLenght_1, 2) . ' м.;' .
            '<br> - после: ' . round($mySpanLenght_2, 2) . ' м.;' .
            '<br><br>Общая длина воздушных пролетов:' .
            '<br> - до внесения правок: ' . round($mySpanLenght701_1, 2) . ' м.;' .
            '<br> - после: ' . round($mySpanLenght701_2, 2) . ' м.;' .
            '<br><br>Общая длина кабельных пролетов:' .
            '<br> - до внесения правок: ' . round($mySpanLenght702_1, 2) . ' м.,' .
            '<br> - после: ' . round($mySpanLenght702_2, 2) . ' м .' .
            '<br><br>ПРИМЕЧАНИЕ: незначительное отличие в длинах возможна из-за способа расчёта: ' .
            '<br>1) на карте - средствами Яндекса;' .
            '<br>2) по базе данных - по формуле с учетом кривизны Земли)';


        if ($myText != '') {
            $myComment .= '<br><br>ПОДРОБНОСТИ' .
                '<br><br>Пролеты с характерными точками, у которых отличается длина:' .
                '<br><br>' . $myText;
        }

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // целостность данных - корректировка длин всех пролетов ЛЭП - ч.2 - обновить в базе
    public function vueRepairSpanLenght_Update($getID, $getUpdate)
    {
        $span = Span::find($getID);
        $span->spanlength = $getUpdate['spanlength'];
        $span->save();

        // возвращаемый параметр - кол-во обновленных строк
        return 1;
    }

    // ------------------------------------------------------------------
    // целостность данных - очистка от несвязанных ни с какой линией ЛЭП строк
    public function vueRepairFindLost($getRegim)
    {
        // все
        $myAclinesAllCount = 0;
        $mySegmentsAllCount = 0;
        $mySegmentsNeedCount = 0;
        $mySegmentsErrorCount = 0;
        $mySpansAllCount = 0;
        $mySpansNeedCount = 0;
        $mySpansErrorCount = 0;
        $myTowersAllCount = 0;
        $myTowersNeedCount = 0;
        $myTowersErrorCount = 0;
        $myCustomersAllCount = 0;
        $myCustomersNeedCount = 0;
        $myCustomersErrorCount = 0;
        $myDisconnectorsAllCount = 0;
        $myDisconnectorsNeedCount = 0;
        $myDisconnectorsErrorCount = 0;
        $myDischargersAllCount = 0;
        $myDischargersNeedCount = 0;
        $myDischargersErrorCount = 0;

        //$getRegim = 'TESTTTTTTTTTTT'; // временно, чтобы на energo.pro не запустили случаной

        // линии
        $aclinesAll = Acline::get();
        if ($aclinesAll) {
            $myAclinesAllCount = count($aclinesAll);
        }

        // сегменты
        // сегменты все
        $segmentsAll = Aclinesegment::get();
        if ($segmentsAll) {
            $mySegmentsAllCount = count($segmentsAll);
            // сегменты "нужные"
            if ($myAclinesAllCount > 0) {
                $segmentsNeed = Aclinesegment::whereIn('acline_id', $aclinesAll->pluck('id'))->get();
                if ($segmentsNeed) {
                    $mySegmentsNeedCount = count($segmentsNeed);
                }
            }
            // сегменты "потерянные"
            if ($mySegmentsNeedCount > 0) {
                $segmentsError = Aclinesegment::whereNotIn('id', $segmentsNeed->pluck('id'))->get();
            } else {
                $segmentsError = Aclinesegment::get();
            }
            if ($segmentsError) {
                $mySegmentsErrorCount = count($segmentsError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Aclinesegment::whereIn('id', $segmentsError->pluck('id'))->delete();
                }
            }
        }

        // пролеты
        // пролеты все
        $spansAll = Span::get();
        if ($spansAll) {
            $mySpansAllCount = count($spansAll);
            // пролеты "нужные"
            if ($mySegmentsNeedCount > 0) {
                $spansNeed = Span::whereIn('aclinesegment_id', $segmentsNeed->pluck('id'))->get();
                if ($spansNeed) {
                    $mySpansNeedCount = count($spansNeed);
                }
            }
            // пролеты "потерянные"
            if ($mySpansNeedCount > 0) {
                $spansError = Span::whereNotIn('id', $spansNeed->pluck('id'))->get();
            } else {
                $spansError = Span::get();
            }
            if ($spansError) {
                $mySpansErrorCount = count($spansError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Span::whereIn('id', $spansError->pluck('id'))->delete();
                }
            }
        }

        // опоры
        // опоры все
        $towersAll = Tower::get();
        if ($towersAll) {
            $myTowersAllCount = count($towersAll);
            // опоры "нужные"
            if ($mySpansNeedCount > 0) {
                $towersNeed = Tower
                    ::whereIn('identifiedobject_id', $spansNeed->pluck('startIO_id'))
                    ->OrwhereIn('identifiedobject_id', $spansNeed->pluck('endIO_id'))
                    ->get();
                if ($towersNeed) {
                    $myTowersNeedCount = count($towersNeed);
                }
            }
            // опоры "потерянные"
            if ($myTowersNeedCount > 0) {
                $towersError = Tower::whereNotIn('id', $towersNeed->pluck('id'))->get();
            } else {
                $towersError = Tower::get();
            }
            if ($towersError) {
                $myTowersErrorCount = count($towersError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Tower::whereIn('id', $towersError->pluck('id'))->delete();
                }
            }
        }

        // потребители
        // потребители все
        $customersAll = Customer::get();
        if ($customersAll) {
            $myCustomersAllCount = count($customersAll);
            // потребители "нужные"
            if ($mySpansNeedCount > 0) {
                $customersNeed = Customer
                    ::whereIn('identifiedobject_id', $spansNeed->pluck('startIO_id'))
                    ->OrwhereIn('identifiedobject_id', $spansNeed->pluck('endIO_id'))
                    ->get();
                if ($customersNeed) {
                    $myCustomersNeedCount = count($customersNeed);
                }
            }
            // потребители "потерянные"
            if ($myCustomersNeedCount > 0) {
                $customersError = Customer::whereNotIn('id', $customersNeed->pluck('id'))->get();
            } else {
                $customersError = Customer::get();
            }
            if ($customersError) {
                $myCustomersErrorCount = count($customersError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Customer::whereIn('id', $customersError->pluck('id'))->delete();
                }
            }
        }

        // разьединители
        // разьединители все
        $disconnectorsAll = Disconnector::get();
        if ($disconnectorsAll) {
            $myDisconnectorsAllCount = count($disconnectorsAll);
            // разьединители "нужные"
            if ($mySpansNeedCount > 0) {
                $disconnectorsNeed = Disconnector
                    ::whereIn('span_id', $spansNeed->pluck('id'))
                    ->where(function ($query) use ($towersNeed, $customersNeed) {
                        $query->whereIn('startIO_id', $towersNeed->pluck('identifiedobject_id'))
                            ->OrwhereIn('startIO_id', $customersNeed->pluck('identifiedobject_id'));
                    })->get();
                if ($disconnectorsNeed) {
                    $myDisconnectorsNeedCount = count($disconnectorsNeed);
                }
            }
            // разьединители "потерянные"
            if ($myDisconnectorsNeedCount > 0) {
                $disconnectorsError = Disconnector::whereNotIn('id', $disconnectorsNeed->pluck('id'))->get();
            } else {
                $disconnectorsError = Disconnector::get();
            }
            if ($disconnectorsError) {
                $myDisconnectorsErrorCount = count($disconnectorsError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Disconnector::whereIn('id', $disconnectorsError->pluck('id'))->delete();
                }
            }
        }

        // разрядники
        // разрядники все
        $dischargersAll = Discharger::get();
        if ($dischargersAll) {
            $myDischargersAllCount = count($dischargersAll);
            // разрядники "нужные"
            if ($mySpansNeedCount > 0) {
                $dischargersNeed = Discharger
                    ::whereIn('startIO_id', $towersNeed->pluck('identifiedobject_id'))
                    ->OrwhereIn('startIO_id', $customersNeed->pluck('identifiedobject_id'))
                    ->get();
                if ($dischargersNeed) {
                    $myDischargersNeedCount = count($dischargersNeed);
                }
            }
            // разрядники "потерянные"
            if ($myDischargersNeedCount > 0) {
                $dischargersError = Discharger::whereNotIn('id', $dischargersNeed->pluck('id'))->get();
            } else {
                $dischargersError = Discharger::get();
            }
            if ($dischargersError) {
                $myDischargersErrorCount = count($dischargersError);

                // обновить в БД
                if ($getRegim === 'update') {
                    Discharger::whereIn('id', $dischargersError->pluck('id'))->delete();
                }
            }
        }

        // возвращаемый параметр
        $myComment = 'СТАТИСТИКА' .
            '<br><br>Всего линий ЛЭП: ' . $myAclinesAllCount .
            '<br>Обнаружено объектов, которые не принадлежат никаким линиям:' .
            '<br><br>Обьект (всего / "нужных") - "потерянных": ' .
            '<br><br>- сегментов (' . $mySegmentsAllCount . ' / ' . $mySegmentsNeedCount . ') - ' . $mySegmentsErrorCount .
            (($getRegim === 'update' and $mySegmentsErrorCount > 0) ? ' - удалены' : '') .
            '<br>- пролетов (' . $mySpansAllCount . ' / ' . $mySpansNeedCount . ') - ' . $mySpansErrorCount .
            (($getRegim === 'update' and $mySpansErrorCount > 0) ? ' - удалены' : '') .
            '<br>- опор (' . $myTowersAllCount . ' / ' . $myTowersNeedCount . ') - ' . $myTowersErrorCount .
            (($getRegim === 'update' and $myTowersErrorCount > 0) ? ' - удалены' : '') .
            '<br>- потребителей (' . $myCustomersAllCount . ' / ' . $myCustomersNeedCount . ') - ' . $myCustomersErrorCount .
            (($getRegim === 'update' and $myCustomersErrorCount > 0) ? ' - удалены' : '') .
            '<br>- разьединителей (' . $myDisconnectorsAllCount . ' / ' . $myDisconnectorsNeedCount . ') - ' . $myDisconnectorsErrorCount .
            (($getRegim === 'update' and $myDisconnectorsErrorCount > 0) ? ' - удалены' : '') .
            '<br>- разрядников (' . $myDischargersAllCount . ' / ' . $myDischargersNeedCount . ') - ' . $myDischargersErrorCount .
            (($getRegim === 'update' and $myDischargersErrorCount > 0) ? ' - удалены' : '') .
            '<br><br>ПРИМЕЧАНИЕ: подразумевается, что разрядники и разьединители могут быть установлены ТОЛЬКО в имеющихся пролетах и на имеющихся опорах или Потребителях. ' .
            'Потребители и опоры - являются вершинами имеющихся пролетов. ' .
            'Пролеты - имеются в существующих сегментах. ' .
            'Сегменты - имеются в существующих линиях. ' .
            '<br>' .
            ($mySegmentsErrorCount > 0 ? '<br>Это сегменты:<br><br>' . implode(', ', $segmentsError->pluck('id')->toArray()) . '<br>' : '') .
            ($mySpansErrorCount > 0 ? '<br>Это пролеты:<br><br>' . implode(', ', $spansError->pluck('id')->toArray()) . '<br>' : '') .
            ($myTowersErrorCount > 0 ? '<br>Это опоры:<br><br>' . implode(', ', $towersError->pluck('id')->toArray()) . '<br>' : '') .
            ($myCustomersErrorCount > 0 ? '<br>Это потребители:<br><br>' . implode(', ', $customersError->pluck('id')->toArray()) . '<br>' : '') .
            ($myDisconnectorsErrorCount > 0 ? '<br>Это разьединители:<br><br>' . implode(', ', $disconnectorsError->pluck('id')->toArray()) . '<br>' : '') .
            ($myDischargersErrorCount > 0 ? '<br>Это разрядники:<br><br>' . implode(', ', $dischargersError->pluck('id')->toArray()) . '<br>' : '');

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // опоры, участвующие в совместном подвесе
    public function vueRepairTowerInDoubleAcline($getRegim)
    {
        // статистика
        $myTowersCount = 0;
        $myTowersDoubleAclineCount = 0;
        $myTowersDoubleAclineText = '';

        // все опоры
        $towers = Tower::select(['id', 'identifiedobject_id'])->get();
        if ($towers) {
            $myTowersCount = count($towers);
            if ($myTowersCount > 0) {
                foreach ($towers as $tower) {
                    $myAclinesObject = $tower->aclinesObject;
                    if ($myAclinesObject['count'] > 1) {
                        $myTowersDoubleAclineCount++;
                        $myTowersDoubleAclineText .=
                            'Наименование: ' . $tower->getName() .
                            '<br>ID: ' . $tower->id .
                            '<br>Линии: ' . $myAclinesObject['text'] .
                            '<br><br>';
                    }
                }
            }
        }

        // возвращаемый параметр
        $myComment = 'СТАТИСТИКА' .
            '<br><br>Всего опор: ' . $myTowersCount .
            '<br><br>Обнаружено, которые участвуют более чем в одной линии: ' . $myTowersDoubleAclineCount;
        if ($myTowersDoubleAclineCount > 0) {
            $myComment .= '<br><br>' . $myTowersDoubleAclineText;
        }

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // целостность данных - прикрепленные изображения
    public function vueRepairImages($getRegim)
    {
        // статистика
        $myImagesTowerMapAll_Count = 0;
        $myImagesTowerMapNeed_Count = 0;
        $myImagesTowerMapNoNeed_Count = 0;
        $myImagesTowerMapAll_Count_Size = 0;
        $myImagesTowerMapNeed_Count_Size = 0;
        $myImagesTowerMapNoNeed_Count_Size = 0;
        $myError = '';

        // все изображения в одном массиве
        $myImagesTowerMapAll = [];

        // массив названий всех изображения опор с карты из базы данных
        $myTowers = Tower::whereNotNull('photos')->where('photos', '<>', '[]')->pluck('photos');
        if ($myTowers and count($myTowers) > 0) {
            foreach ($myTowers as $myTower) {
                $myImages = json_decode($myTower, true);
                if ($myImages and count($myImages) > 0) {
                    foreach ($myImages as $myImage) {
                        array_push($myImagesTowerMapAll, $myImage);
                    }
                }
            }
        }

        // путь к изображениям опор с карты
        $pathTowerMap = public_path() . '/uploads/models/map/photos';
        // проверить читается ли эта диретория
        if (is_readable($pathTowerMap)) {
            // прочитать содержимое директории
            $contentDir = opendir($pathTowerMap);
            // сканировать диреторию
            while ($fileName = readdir($contentDir)) {
                if ($fileName == ' . ' || $fileName == ' ..') continue;
                // полный путь до файла
                $myFullPath = $pathTowerMap . DIRECTORY_SEPARATOR . $fileName;
                if (is_file($myFullPath)) {
                    // да, это файл
                    // его размер
                    $myFileSize = File::size($myFullPath);

                    $myImagesTowerMapAll_Count++;
                    $myImagesTowerMapAll_Count_Size += $myFileSize;

                    // поиск в базе
                    if (in_array($fileName, $myImagesTowerMapAll)) {
                        // да, есть в массиве
                        $myImagesTowerMapNeed_Count++;
                        $myImagesTowerMapNeed_Count_Size += $myFileSize;
                    } else {
                        // нет в массиве
                        $myImagesTowerMapNoNeed_Count++;
                        $myImagesTowerMapNoNeed_Count_Size += $myFileSize;
                    }
                }
            }
            // закрыть дескриптор диреториии
            closedir($contentDir);
        } else {
            $myError = 'Возникла ошибка при открытии папки с изображениями опор на карте "Нет прав!';
        }

        // возвращаемый параметр
        $myComment = 'СТАТИСТИКА' .
            (($myError === '') ? '' : '<br><br>' . $myError) .
            '<br><br>Обнаружено прикрепленных изображений к опорам на карте: ' .
            '<br><br>- всего: ' . $myImagesTowerMapAll_Count . ' шт. (' . round($myImagesTowerMapAll_Count_Size / 1000000, 1) . ' Мб)' .
            '<br>- из них указано в базе: ' . $myImagesTowerMapNeed_Count . ' шт. (' . round($myImagesTowerMapNeed_Count_Size / 1000000, 1) . ' Мб)' .
            '<br>- из них не указано в базе (можно удалить): ' . $myImagesTowerMapNoNeed_Count . ' шт. (' . round($myImagesTowerMapNoNeed_Count_Size / 1000000, 1) . ' Мб)';

//            '<br>Помеченных на удаление: ' . $myStatistikaItogoKolStrDeletedAt .
//            ($getRegim === 'update' ? '<br><br>Удалено' : '') .
//            '<br><br>Таблицы (строк / помеченных на удаление) - можно высвободить место %:' .
//            '<br><br>' . 111;

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // целостность данных - сжатие базы за счет удаления помеченных на удаление
    public
    function vueRepairDeletedAt($getRegim)
    {
        // имя базы данных
        $myNameBaza = 'Tables_in_' . env('DB_DATABASE');
        // список всех таблиц
        $tables = DB::select('SHOW TABLES');

        $myAnaliz = collect();
        foreach ($tables as $table) {

            // имя таблицы
            $myTableName = $table->$myNameBaza;

            // кол-во записей всего
            $myKolStrAll = DB::table($myTableName)->count();

            //$aaa = DB::select('SELECT table_name, round(data_length/1024) as data_length_kb, round(data_free/1024) as data_free_kb from information_schema.tables  order by data_free_kb');
            //dd($aaa);

            // проверка, есть ли столбец deleted_at
            $isDeletedAt = Schema::hasColumn($myTableName, 'deleted_at');

            // кол-во удаленных записей и сколько можно высвободить места
            $myKolStrDeletedAt = 0;
            $myMayFreeSpace = 0;
            if ($isDeletedAt) {
                $myKolStrDeletedAt = DB::table($myTableName)->where('deleted_at', '<>', null)->count();
                if ($myKolStrAll > 0 and $myKolStrDeletedAt > 0) {
                    $myMayFreeSpace = round($myKolStrDeletedAt * 100 / $myKolStrAll, 1);

                    // обновить в БД
                    if ($getRegim === 'update') {
                        DB::select('DELETE FROM ' . $myTableName . ' WHERE deleted_at IS NOT NULL');
                    }
                }
            }

            // обновить в БД
            // > 300 мс может иди и упасть в ошиьбку!
            if ($getRegim === 'update') {
                // оптимизировать таблицу
                DB::select('OPTIMIZE TABLE ' . $myTableName);
            }

            // записать в коллекцию
            $myAnaliz->push([
                'table_name' => $myTableName,
                'kol_str_all' => $myKolStrAll,
                'is_deleted_at' => $isDeletedAt,
                'kol_str_deleted_at' => $myKolStrDeletedAt,
                'free_space' => $myMayFreeSpace,
            ]);
        }

        // отсортировать по кол-ву возможного высвобождения свободного места
        //$myAnaliz = $myAnaliz->sortByDesc('kol_str_deleted_at'); //->sortByDesc('kol_str_all');
        $myAnaliz = $myAnaliz->sort(function ($a, $b) {
            return $a['free_space'] === $b['free_space'] ? $b['kol_str_all'] <=> $a['kol_str_all'] : $b['free_space'] <=> $a['free_space'];
        });

        // подготовить в виде текстовой строки
        $myComment = '';
        $myStatistikaItogoKolStrAll = 0;
        $myStatistikaItogoKolStrDeletedAt = 0;
        foreach ($myAnaliz as $item) {

            // одна строка
            $myComment .=
                '- ' . $item['table_name'] .
                ' (' . $item['kol_str_all'] . ' / ' . $item['kol_str_deleted_at'] . ')' .
                (($item['free_space'] > 0) ? ' - ' . $item['free_space'] . ' %' : '') .
                (($getRegim === 'update' and $item['kol_str_deleted_at'] > 0) ? ' - удалены "удаленные"' : '');
            $myComment .= '<br>';

            // статистика
            $myStatistikaItogoKolStrAll += $item['kol_str_all'];
            $myStatistikaItogoKolStrDeletedAt += $item['kol_str_deleted_at'];
        }

        // возвращаемый параметр
        $myComment = 'СТАТИСТИКА' .
            '<br><br>Обнаружено таблиц: ' . count($myAnaliz) .
            '<br>Общее число строк: ' . $myStatistikaItogoKolStrAll .
            '<br>Помеченных на удаление: ' . $myStatistikaItogoKolStrDeletedAt .
            ($getRegim === 'update' ? '<br><br>ВСЕ ТАБЛИЦЫ ОПТИМИЗИРОВАНЫ!' : '') .
            '<br><br>Таблицы (строк / помеченных на удаление) - можно высвободить место %:' .
            '<br><br>' . $myComment;

        // возвращаемый параметр
        return $myComment;
    }

    // ------------------------------------------------------------------
    // дописать всем характерным точкам start-end ч.1 (чтоб концы были соединены, а не висели в воздухе. После моего обновленяи collection И изменения алгоритма расчета длины)
    // это разовый скрипт, сам с url запускаю
    public
    function newPoints($getRegim = null)
    {
        // статистика
        $mySpanCount = 0;
        $mySpanCountNoStartEnd = 0;
        $myNewPoint = 0;
        $myNewPointStart = 0;
        $myNewPointEnd = 0;
        $myOldPointStart = 0;
        $myOldPointEnd = 0;
        $mySpanSave = 0;

        // все пролеты
        $spans = Span::with('startIO', 'endIO')->get();
        if ($spans) {
            foreach ($spans as $span) {
                // статистика
                $mySpanCount++;

                // текущий пролет
                $currentSpanID = $span->id;
                $currentStartLat = $span->startIO->lat;
                $currentStartLong = $span->startIO->long;
                $currentEndLat = $span->endIO->lat;
                $currentEndLong = $span->endIO->long;
                $currentPoints = json_decode($span->points, true);

                if ($currentStartLat !== null and $currentStartLong !== null and $currentEndLat !== null and $currentEndLong !== null) {

                    //$currentStart = [];
                    $currentStart = [(string)$span->startIO->lat, (string)$span->startIO->long];
                    //$currentEnd = [];
                    $currentEnd = [(string)$span->endIO->lat, (string)$span->endIO->long];

                    echo "Пролет:";
                    echo "<br>";
                    echo "$currentSpanID:";
                    echo "<br>";
                    echo "Начало:";
                    echo "<br>";
                    echo print_r($currentStart);
                    echo "<br>";
                    echo "Конец:";
                    echo "<br>";
                    echo print_r($currentEnd);
                    echo "<br>";
                    echo "Характерные точки:";
                    echo "<br>";
                    echo print_r($currentPoints);
                    echo "<br>";

                    if ($currentPoints === null or $currentPoints === '' or $currentPoints === []) {
                        // нет, нету - вставить
                        echo "Не было ничего, вставил";
                        echo "<br>";

                        // вставить
                        $currentPoints = [$currentStart, $currentEnd];

                        echo "Характерные точки стали:";
                        echo "<br>";
                        echo print_r($currentPoints);
                        echo "<br>";

                        // обновить в БД
                        if ($getRegim === 'save') {
                            $mySpanSave += self::newPointsSave($currentSpanID, $currentPoints);
                        }

                        // статистика
                        $myNewPoint++;

                    } else {

                        // проверка на start (внимание, есть разница число-символ! in_array($currentStart, $currentPoints))
                        $myHave = false;
                        foreach ($currentPoints as $item) {
                            if ((string)$item[0] === (string)$currentStartLat and (string)$item[1] === (string)$currentStartLong) {
                                // есть
                                $myHave = true;

                                // статистика
                                $myOldPointStart++;

                                break;
                            }
                        }
                        if ($myHave === false) {
                            // нет, нету - дописать
                            echo "Начала нету, надо дописать";
                            echo "<br>";

                            // вставить в начало
                            array_unshift($currentPoints, $currentStart);

                            echo "Характерные точки стали:";
                            echo "<br>";
                            echo print_r($currentPoints);
                            echo "<br>";

                            // обновить в БД
                            if ($getRegim === 'save') {
                                $mySpanSave += self::newPointsSave($currentSpanID, $currentPoints);
                            }

                            // статистика
                            $myNewPointStart++;
                        }

                        // проверка на end (внимание, есть разница число-символ! in_array($currentStart, $currentPoints))
                        $myHave = false;
                        foreach ($currentPoints as $item) {
                            if ((string)$item[0] === (string)$currentEndLat and (string)$item[1] === (string)$currentEndLong) {
                                // есть
                                $myHave = true;

                                // статистика
                                $myOldPointEnd++;

                                break;
                            }
                        }
                        if ($myHave === false) {
                            // нет, нету - дописать
                            echo "Конца нету, надо дописать";
                            echo "<br>";

                            // вставить в конец
                            array_push($currentPoints, $currentEnd);

                            echo "Характерные точки стали:";
                            echo "<br>";
                            echo print_r($currentPoints);
                            echo "<br>";

                            // обновить в БД
                            if ($getRegim === 'save') {
                                $mySpanSave += self::newPointsSave($currentSpanID, $currentPoints);
                            }

                            // статистика
                            $myNewPointEnd++;
                        }
                    }
                } else {
                    // статистика
                    $mySpanCountNoStartEnd++;

                    echo "Пролет: " . $currentSpanID . ' не имеет начала или конца!';
                    echo "<br>";
                }
                echo "<br>";
            }
        }

        echo "Отчет о проделанной работе:";
        echo "<br>";
        echo "Пролетов обработано: " . $mySpanCount;
        echo "<br>";
        echo "Не найдено начала или конца у : " . $mySpanCountNoStartEnd;
        echo "<br>";
        echo "Необходимо добавить в характерные точки вместо пустоты : " . $myNewPoint;
        echo "<br>";
        echo "Необходимо добавить в начало характерных точек: " . $myNewPointStart;
        echo "<br>";
        echo "Необходимо добавить в конец характерных точек: " . $myNewPointEnd;
        echo "<br>";
        echo "Начало уже имелось в характерных точках: " . $myOldPointEnd;
        echo "<br>";
        echo "Конец уже имелось в характерных точках: " . $myOldPointStart;
        echo "<br>";
        echo "Сохранено в базе: " . $mySpanSave;
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
    }

    // ------------------------------------------------------------------
    // дописать всем характерным точкам start-end - ч.2 сохранение
    // разовый скрипт
    public
    function newPointsSave($getSpanID, $getSpanNewPoint)
    {
        $span = Span::find($getSpanID);
        $span->points = $getSpanNewPoint;
        $span->save();

        // возвращаемый параметр
        return 1;
    }

    // ------------------------------------------------------------------
    // очистить все линии и все что с ними связано (для отладки)
    // !!! потом убрать - это разовый скрипт, сам с url запускаю
    public
    function trancateAll()
    {
        // способ 1- через модели

        // отключить проверку ключей
        Schema::disableForeignKeyConstraints();

        // очистка таблиц
        Acline::truncate();
        Aclinesegment::truncate();
        Span::truncate();
        Tower::truncate();
        Customer::truncate();
        Disconnector::truncate();
        Discharger::truncate();
        Crossing::truncate();
        Identifiedobject::truncate();

        // включить проверку ключей
        Schema::enableForeignKeyConstraints();

        // способ 2 - через сырыре запросы
        // отключить проверку ключей
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // выполнние сырого запроса
        //DB::select('TRUNCATE TABLE `acline`');
        //DB::select('TRUNCATE TABLE `aclinesegment“`');
        //DB::select('TRUNCATE TABLE `span`');
        //DB::select('TRUNCATE TABLE `tower`');
        //DB::select('TRUNCATE TABLE `customer`');
        //DB::select('TRUNCATE TABLE `disconnector`');
        //DB::select('TRUNCATE TABLE `discharger`');
        //DB::select('TRUNCATE TABLE `crossing`');
        //DB::select('TRUNCATE TABLE `identifiedobject`');

        // включить проверку ключей
        //DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // возвращаемый параметр
        echo "Таблицы, связанные с ЛЭП - очищены!";
    }
}
