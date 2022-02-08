<?php

namespace App\Http\Controllers\backend\Acline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

// для импорта с Excel
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AclineReport2;
use App\Exports\AclineReport3Sheet;

// контроллер модели (унаследую)
class AclineReportController extends AclineController
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
    // отчеты
    public function report($report_id = null, $acline_id = null)
    {
        // проверка, что номер отчета $reportN указан. Иначе - досрочный выход
        if (is_null($report_id)) return;

        // смотря какой номер отчета передали
        switch ($report_id) {
            case '1':
                // паспорт
                $myReturn = self::passport($acline_id);
                break;
            case '2':
                // отчет-2 (посегментный вывод одной линии)
                $myReturn = self::report2($acline_id);
                break;
            case '3':
                // отчет-3 - итоговый по всем линиям - опирается на отчет-2 (посегментный вывод одной линии)
                $myReturn = self::report3();
                break;
        }
        return $myReturn;
    }

    // ------------------------------------------------------------------
    // отчет-2 (посегментный вывод одной линии)
    public function report2($getID)
    {
        // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
        $thisLineDatas = self::getThisLineDatas($getID);

        // отчет-2 (вся логика)
        $myReport = self::report2_content($thisLineDatas);

        // скачать excel-ий документ
        return Excel::download(new AclineReport2($myReport), 'acline-' . $getID . '-report2.xlsx');
    }

    // ------------------------------------------------------------------
    // отчет-3 - итоговый по всем линиям - опирается на отчет-2 (посегментный вывод одной линии)
    public function report3()
    {
        // итоговый массив линий по классу напряжения
        $myReportItogo = array('0,4' => [], '6-10' => []);

        // сканировать все линии в несколько проходов по классу напряжения
        foreach ($myReportItogo as $myVoltage => $value) {

            // итоговая расчетная длина линий данного класса напряжения
            $aclinesLenght = 0;
            // итоговое кол-во опор
            $aclineTowers = 0;

            // условие запроса
            if ($myVoltage == '0,4') {
                $myUsl = '=';
            } else {
                $myUsl = '<>';
            }

            // получить линии по условию
            $aclines = Acline
                ::selectRaw('acline.id')
                ->leftJoin('identifiedobject as i', 'acline.identifiedobject_id', '=', 'i.id')
                ->where('i.voltage_id', $myUsl, 380)
                ->whereNull('acline.deleted_at')
                ->whereNull('i.deleted_at')
                ->orderby('i.voltage_id', 'asc')
                ->get();

            if ($aclines and count($aclines) > 0) {
                foreach ($aclines as $acline) {

                    // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
                    $thisLineDatas = self::getThisLineDatas($acline->id);

                    // отчет-2 (вся логика)
                    $myReport = self::report2_content($thisLineDatas);

                    if (count($myReport['aclineData']) > 0) {
                        if (count($value) > 0) {
                            $value = array_merge($value, $myReport['aclineData']);
                        } else {
                            $value = $myReport['aclineData'];
                        }
                        // вставить пустую строчку после очередной ЛЭП
                        $value [] = [];
                    }

                    // итоговая расчетная длина линий данного класса напряжения
                    $aclinesLenght += $myReport['aclineLenght'];
                    // итоговое кол-во опор
                    $aclineTowers += $myReport['aclineTowers'];
                }
            }

            // итоговая строка по классу напряжения
            $myReportItogo [$myVoltage] = [
                'aclineData' => $value,
                'aclineName' => 'Все линии',
                'aclineVoltage' => $myVoltage,
                'aclineLenght' => $aclinesLenght,
                'aclineTowers' => $aclineTowers,
            ];
        }

        // скачать excel-ий документ
        return Excel::download(new AclineReport3Sheet($myReportItogo), 'aclines-report3.xlsx');
    }

    // ------------------------------------------------------------------
    // отчет-2 (вся логика)
    public static function report2_content($getAclineDatas, $getRegimOnlyNameSegment = false)
    {
        $acline = $getAclineDatas['acline'];
        $aclinesegments = $getAclineDatas['aclinesegments'];
        $spans = $getAclineDatas['spans'];
        $towers = $getAclineDatas['towers'];
        $customers = $getAclineDatas['customers'];

        // получить еще коллекцию IDs для проверки кому принадлежат вершины
        $customersIOIDs = $customers->pluck('identifiedobject_id')->unique();
        $towerFictTpsIOIDs = $towers->where('fict_tp', 1)->pluck('identifiedobject_id')->unique();

        // данные для всего отчета
        $aclineName = $acline->identifiedobject->name; // имя линии
        $aclineVoltage = $acline->identifiedobject->voltage_id; // напряжение
        $aclineLenght = 0; // итоговая расчетная длина линии

        // итоговое кол-во опор
        $aclineTowers = $towers->where('fict_tp', 0)->count();
        // накопительный массив опор, которые уже подсчитали при построчном выводе сегментов, чтобы не было дубликатов
        $myAclineSumTowerIDs = [];

        // макет шаблона
        $myReport = [];
        $myNN = 0;

        // до таблицы
        $myReport [] = [
            'thisAclineName' => true,
            '1' => $aclineName
        ];
        $myReport [] = [
            'thisShapka' => true,
        ];

        // регулярки
        // любое кол-во символов, любое кол-во пробелов или отсутсвие, снова цифра, возможно буква, разделите, снова цифра
        $pattern_1A_1 = '/' . '\D*\d\w*?([A-zА-я])?(-|.|\/)\d' . '/';
        // '/\D*\s*\d\?=[a-z]\s?(-|.|\/)\d/'
        // оставить только цифры
        $pattern_0_9 = '/' . '[^0-9]' . '/';
        // ТП есть в названии
        $pattern_TP = '/' . 'ТП' . '/ui'; // utf-8, регситронезависимо (для онлайн теста g добавить, чтоб было глобально, не возвращать только первую позицию)
        // нет букв после цифры в имени
        $pattern_15End = '/' . '\D*\d\w*?([A-zА-я])?(-|.|\/)\d' . '/';

        // сформировать сводный массив сегменитов со всеми их данными
        $mySegmentsAnaliz = array();
        $myPointsAll = collect();
        if (count($aclinesegments) > 0) {
            foreach ($aclinesegments as $key => $segment) {

                // текущий сегмент
                $mySegmentID = $segment->id;

                // пролеты сегмента
                $mySpans = $spans->where('aclinesegment_id', $mySegmentID);
                $mySpanPoints = collect();
                $mySpanType = null;
                $mySegmentLenght = 0;
                $lengths = [];
                $mySegmentWith = 'towers';
                if (count($mySpans) > 0) {

                    foreach ($mySpans as $span) {

                        // все вершины для определения точки начала
                        // исключить потребителей
                        if (!$customersIOIDs->contains($span->startIO->id) and
                            !$customersIOIDs->contains($span->endIO->id)) {
                            // накопительный массив вершин пролетов
                            $myPointsAll->push(
                                [
                                    'id' => $span->startIO->id,
                                    'segment_id' => $mySegmentID,
                                    'name' => $span->startName,
                                    'para_name' => $span->endName,
                                    'para_name_digit' => (int)preg_replace($pattern_0_9, '', $span->endName),
                                ],
                                [
                                    'id' => $span->endIO->id,
                                    'segment_id' => $mySegmentID,
                                    'name' => $span->endName,
                                    'para_name' => $span->startName,
                                    'para_name_digit' => (int)preg_replace($pattern_0_9, '', $span->startName),
                                ]
                            );
                        }

                        // узнать, к чему принадлежит одна из вершин - ТП,опора или потребитель
                        // проверка на потребителя
                        if ($customersIOIDs->contains($span->startIO->id) or $customersIOIDs->contains($span->endIO->id)) {
                            $mySegmentWith = 'customer';
                        }

                        // проверка на ТП
                        if ($towerFictTpsIOIDs->contains($span->startIO->id) or $towerFictTpsIOIDs->contains($span->endIO->id)) {
                            $mySegmentWith = 'towerFictTp';
                        }

                        // накопительный массив вершин пролетов
                        $mySpanPoints->push(
                            [
                                'id' => $span->startIO->id,
                                'name' => $span->startName,
                                'name_digit' => preg_replace($pattern_0_9, '', $span->startName),
                            ],
                            [
                                'id' => $span->endIO->id,
                                'name' => $span->endName,
                                'name_digit' => preg_replace($pattern_0_9, '', $span->endName),
                            ]
                        );

                        // итоговая длина сегмента
                        $mySegmentLenght += (float)$span->spanlength;
                        $lengths[$span->id] = (float)$span->spanlength;
                        // тип (701 или 702 - не должно меняться)
                        if (is_null($mySpanType) and $mySpanType <> 'error') {
                            $mySpanType = (int)($span->spantype);
                        } else {
                            if ($mySpanType <> $span->spantype) {
                                $mySpanType = 'error';
                            }
                        }
                    }
                }

                // опоры этого сегмента
                $mySegmentTowerItogo = 0;
                $mySegmentTowerIronConcrete = 0;
                $mySegmentTowerWoodWithAnnex = 0;
                $mySegmentTowerWood = 0;
                $mySegmentTowerMetal = 0;
                $mySegmentTowerOther = 0;
                $myTowerNames = [];
                if (count($mySpanPoints) > 0) {
                    $myTowers = $towers->where('fict_tp', 0)->whereIn('identifiedobject_id', $mySpanPoints->pluck('id'))->whereNotIn('id', $myAclineSumTowerIDs);
                    if (count($myTowers) > 0) {
                        foreach ($myTowers as $tower) {

                            // записать в накопительный массив опор, которые уже подсчитали, чтобы не было дублей
                            $myAclineSumTowerIDs [] = $tower->id;

                            // материал опоры
                            $myTowerMaterial = $tower->towermaterial_id;
                            switch ($myTowerMaterial) {
                                case 1:
                                    // дерево
                                    $mySegmentTowerWood++;
                                    if ($tower->annex == 'metal' or $tower->annex == 'ironConcrete') {
                                        // с приставкой
                                        $mySegmentTowerWoodWithAnnex++;
                                    }
                                    break;
                                case 2:
                                    // металл
                                    $mySegmentTowerMetal++;
                                    break;
                                case 3:
                                    // ж/б
                                    $mySegmentTowerIronConcrete++;
                                    break;
                                default:
                                    // прочий материал или не указан
                                    $mySegmentTowerOther++;
                            }

                            // накопительный массив имен опор
                            $myTowerNames [] = $tower->identifiedobject->name;

                            // итого опор
                            $mySegmentTowerItogo++;
                        }
                    }
                }

                // накопительный массив вершин пролетов
                // уникальные вершины
                $mySpanPointsUnique = $mySpanPoints->unique('id');
                // вершины с повтором
                $mySpanPointsDublicates = $mySpanPoints->pluck('id')->duplicates();
                // две крайние вершины сегмента
                $mySpanPoints12 = $mySpanPointsUnique->whereNotIn('id', $mySpanPointsDublicates)->sortBy('name_digit');

                // сортировка, начальная и конечная точка текущего сегмента
                if (count($mySpanPoints12) > 0) {
                    $mySegmentStartPoint = $mySpanPoints12->first();
                    $mySegmentEndPoint = $mySpanPoints12->last();
                    $mySegmentName = $mySegmentStartPoint['name'] . ' - ' . $mySegmentEndPoint['name'];
                } else {
                    $mySegmentStartPoint = null;
                    $mySegmentEndPoint = null;
                    $mySegmentName = 'Сегмент ID = ' . $segment->id;
                }

                // записать данные по сегменту
                $mySegmentsAnaliz [$mySegmentID] = [
                    'segmentID' => $mySegmentID,
                    'segmentName' => $mySegmentName, // первичное имя до анализа по веткам
                    'segmentStartPoint' => $mySegmentStartPoint,
                    'segmentEndPoint' => $mySegmentEndPoint,
                    'spansPoints' => $mySpanPoints,
                    'spansPointsNames' => $mySpanPoints->unique()->implode('name', ', '),
                    'spansPointsNamesDigit' => $mySpanPoints->unique()->sortBy('name_digit')->implode('name_digit', ', '),
                    'spansPointsIds' => $mySpanPoints->unique()->sortBy('name_digit')->implode('id', ','),
                    'segmentWith' => $mySegmentWith,
                    'group' => '',
                    'sort' => PHP_INT_MAX,
                    'segmentType' => $mySpanType,
                    'segmentWireMark' => $segment->wiremark->assetinfokey,
                    'segmentLenght' => $mySegmentLenght,
                    'lengths' => $lengths,
                    'towerItogo' => $mySegmentTowerItogo == 0 ? '' : $mySegmentTowerItogo,
                    'towerIronConcrete' => $mySegmentTowerIronConcrete == 0 ? '' : $mySegmentTowerIronConcrete,
                    'towerMetal' => $mySegmentTowerMetal == 0 ? '' : $mySegmentTowerMetal,
                    'towerWood' => $mySegmentTowerWood == 0 ? '' : $mySegmentTowerWood,
                    'towerWoodWithAnnex' => $mySegmentTowerWoodWithAnnex == 0 ? '' : $mySegmentTowerWoodWithAnnex,
                    'towerOther' => $mySegmentTowerOther == 0 ? '' : $mySegmentTowerOther,
                ];
            }
        }

        // ----------------------
        // уникальные вершины
        $myPointsUnique = $myPointsAll->unique('id');
        // вершины с повтором
        $myPointsDublicates = $myPointsAll->pluck('id')->duplicates();
        // все крайние вершины (нужная начальная - в самом вверху)
        $myPoints12 = $myPointsUnique->whereNotIn('id', $myPointsDublicates)->sortBy('para_name_digit');

        // ----------------------
        // вершины для магистрали
        $myPointsAll_Magistral = collect();
        foreach ($myPoints12 as $item) {
            if (preg_match($pattern_TP, $item['name']) and !preg_match($pattern_1A_1, $item['para_name'])) {
                // это ТП и проверить только вторую пару-вершину
                $myPointsAll_Magistral->push($item);
            } else {
                // это не ТП
                // проверить вершину и ее вторую пару-вершину
                if (!preg_match($pattern_1A_1, $item['name']) and !preg_match($pattern_1A_1, $item['para_name'])) {
                    $myPointsAll_Magistral->push($item);
                }

            }
        }
//        $myPointsAll_Magistral = $myPoints12->filter(function ($myPoints12) use ($pattern_1A_1, $pattern_TP) {
//
//            return
//                (!preg_match($pattern_TP, $myPoints12['name']) and !preg_match($pattern_1A_1, $myPoints12['name'])) or
//                (!preg_match($pattern_TP, $myPoints12['para_name']) and !preg_match($pattern_1A_1, $myPoints12['para_name'])) or
//                (preg_match($pattern_TP, $myPoints12['name'] or preg_match($pattern_TP, $myPoints12['para_name'])));

//            return
//                !preg_match($pattern_1A_1, $myPoints12['name']) and
//                !preg_match($pattern_1A_1, $myPoints12['para_name']);
//        });

        if (is_object($myPointsAll_Magistral) and count($myPointsAll_Magistral) > 0) {

            // начальная точка разбора схемы
            $myCurrentPoint = $myPointsAll_Magistral->first();
            $myCurrentSegment = collect($mySegmentsAnaliz)->filter(function ($mySegmentsAnaliz) use ($myCurrentPoint) {
                return
                    isset($mySegmentsAnaliz['segmentStartPoint']['id']) and
                    isset($mySegmentsAnaliz['segmentEndPoint']['id']) and
                    ($mySegmentsAnaliz['segmentStartPoint']['id'] == $myCurrentPoint['id'] or
                        $mySegmentsAnaliz['segmentEndPoint']['id'] == $myCurrentPoint['id']) and
                    $mySegmentsAnaliz['segmentWith'] <> 'customer'; // чтоб не шел на Потребителя
            });
            if (is_object($myCurrentSegment) and count($myCurrentSegment) > 0) {

                // текущий сегмент
                $myCurrentSegment = $myCurrentSegment->first();

                // пройтись в цикле от указанной вершины до конца по магистрали
                $myNeedAnaliz = true;
                $mySort = 0;
                while ($myNeedAnaliz) {
                    $mySort++;
                    // начальная точка сегмента
                    $mySegmentStartPointName = $myCurrentPoint['name'];
                    // следующая точка
                    $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentEndPoint'] : $myCurrentSegment['segmentStartPoint']; // взять противоположное
                    // конечная точка сегмента
                    $mySegmentEndPointName = $myCurrentPoint['name'];
                    // название сегмента
                    $mySegmentName = $mySegmentStartPointName . ' - ' . $mySegmentEndPointName;
                    //echo "Сегмент магистрали: " . $mySegmentName . "<br>";

                    // записать имя сегмента с правильным расположением вершин, номер группы и сортировку (магистраль = 0, № отпайки)
                    $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['segmentName'] = $mySegmentName;
                    $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['group'] = 'магистраль';
                    $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['sort'] = $mySort;

                    // найти от этого конца следующий сегмент(-ы)
                    $myCurrentSegment = collect($mySegmentsAnaliz)->filter(function ($mySegmentsAnaliz) use ($myCurrentPoint, $myCurrentSegment, $pattern_1A_1) {
                        return
                            isset($mySegmentsAnaliz['segmentStartPoint']['id']) and
                            isset($mySegmentsAnaliz['segmentEndPoint']['id']) and
                            ($mySegmentsAnaliz['segmentStartPoint']['id'] == $myCurrentPoint['id'] or
                                $mySegmentsAnaliz['segmentEndPoint']['id'] == $myCurrentPoint['id']) and
                            $mySegmentsAnaliz['segmentWith'] <> 'customer' and // чтоб не шел на Потребителя
                            $mySegmentsAnaliz['group'] == '' and // чтоб не был определен еще как магистраль (был пустой!)
                            $mySegmentsAnaliz['segmentID'] <> $myCurrentSegment['segmentID'] and // чтоб не был текущим
                            !preg_match($pattern_1A_1, $mySegmentsAnaliz['spansPointsNames']); // чтоб не был отпайкой
                    });
                    if (is_object($myCurrentSegment) and count($myCurrentSegment) > 0) {
                        // продолжение есть

                        // текущий сегмент
                        $myCurrentSegment = $myCurrentSegment->first();
                        $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentStartPoint'] : $myCurrentSegment['segmentEndPoint']; // взять такуе же

                    } else {
                        // продолжения нет - выход из цикла
                        $myNeedAnaliz = false;
                    }
                }
            }
        }

        // ----------------------
        // вершины отпаек
        $myPointsAll_Otpayka = $myPoints12->filter(function ($myPoints12) use ($pattern_1A_1) {
            return
                preg_match($pattern_1A_1, $myPoints12['name']) or
                preg_match($pattern_1A_1, $myPoints12['para_name']);
        });
        if (is_object($myPointsAll_Otpayka) and count($myPointsAll_Otpayka) > 0) {

            $myOtpaykaN = 0;
            foreach ($myPointsAll_Otpayka as $Otpayka) {

                // начальная точка разбора схемы
                $myCurrentPoint = $Otpayka;
                $myCurrentSegment = collect($mySegmentsAnaliz)->filter(function ($mySegmentsAnaliz) use ($myCurrentPoint) {
                    return
                        isset($mySegmentsAnaliz['segmentStartPoint']['id']) and
                        isset($mySegmentsAnaliz['segmentEndPoint']['id']) and
                        ($mySegmentsAnaliz['segmentStartPoint']['id'] == $myCurrentPoint['id'] or
                            $mySegmentsAnaliz['segmentEndPoint']['id'] == $myCurrentPoint['id']) and
                        $mySegmentsAnaliz['segmentWith'] <> 'customer' and // чтоб не шел на Потребителя
                        $mySegmentsAnaliz['group'] == ''; // чтоб не был определен еще как магистраль (был пустой!)
                });
                if (is_object($myCurrentSegment) and count($myCurrentSegment) > 0) {
                    // номер отпайки
                    $myOtpaykaN++;

                    // текущий сегмент
                    $myCurrentSegment = $myCurrentSegment->first();

                    // движение от конца в сторону магистрали
                    // пройтись в цикле от указанной вершины до встречи с другой группой
                    $myNeedAnaliz = true;
                    if (!isset($mySort)) $mySort = 0;
                    while ($myNeedAnaliz) {
                        $mySort++;
                        // следующая точка
                        $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentEndPoint'] : $myCurrentSegment['segmentStartPoint']; // взять противоположное

                        // запомнить сегмент
                        $myCurrentSegment_OldValue = $myCurrentSegment;

                        // найти от этого конца следующий сегмент(-ы)
                        $myCurrentSegment = collect($mySegmentsAnaliz)->filter(function ($mySegmentsAnaliz) use ($myCurrentPoint, $myCurrentSegment) {
                            return
                                isset($mySegmentsAnaliz['segmentStartPoint']['id']) and
                                isset($mySegmentsAnaliz['segmentEndPoint']['id']) and
                                ($mySegmentsAnaliz['segmentStartPoint']['id'] == $myCurrentPoint['id'] or
                                    $mySegmentsAnaliz['segmentEndPoint']['id'] == $myCurrentPoint['id']) and
                                $mySegmentsAnaliz['segmentWith'] <> 'customer' and // чтоб не шел на Потребителя
                                $mySegmentsAnaliz['group'] == '' and // чтоб не был определен еще как магистраль (был пустой!)
                                $mySegmentsAnaliz['segmentID'] <> $myCurrentSegment['segmentID']; // чтоб не был текущим
                        });
                        if (is_object($myCurrentSegment) and count($myCurrentSegment) > 0) {
                            // продолжение есть

                            // текущий сегмент и точка
                            $myCurrentSegment = $myCurrentSegment->first();
                            $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentStartPoint'] : $myCurrentSegment['segmentEndPoint']; // взять такуе же

                        } else {
                            // продолжения нет - выход из первого цикла. Но теперь обратное движение - от магистрали к концу отпайки
                            $myNeedAnaliz = false;
                            $myCurrentSegment = $myCurrentSegment_OldValue;
                            // пройтись в цикле от указанной вершины до конца
                            $myNeedAnaliz2 = true;
                            while ($myNeedAnaliz2) {
                                $mySort++;
                                // начальная точка сегмента
                                $mySegmentStartPointName = $myCurrentPoint['name'];
                                // следующая точка
                                $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentEndPoint'] : $myCurrentSegment['segmentStartPoint']; // взять противоположное
                                // конечная точка сегмента
                                $mySegmentEndPointName = $myCurrentPoint['name'];
                                // название сегмента
                                $mySegmentName = $mySegmentStartPointName . ' - ' . $mySegmentEndPointName;
                                //echo "Сегмент магистрали: " . $mySegmentName . "<br>";

                                // записать имя сегмента с правильным расположением вершин, номер группы и сортировку (магистраль = 0, № отпайки)
                                $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['segmentName'] = $mySegmentName;
                                $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['group'] = 'отпайка ' . $myOtpaykaN;
                                $mySegmentsAnaliz[$myCurrentSegment['segmentID']]['sort'] = $mySort;

                                // найти от этого конца следующий сегмент(-ы)
                                $myCurrentSegment = collect($mySegmentsAnaliz)->filter(function ($mySegmentsAnaliz) use ($myCurrentPoint, $myCurrentSegment) {
                                    return
                                        isset($mySegmentsAnaliz['segmentStartPoint']['id']) and
                                        isset($mySegmentsAnaliz['segmentEndPoint']['id']) and
                                        ($mySegmentsAnaliz['segmentStartPoint']['id'] == $myCurrentPoint['id'] or
                                            $mySegmentsAnaliz['segmentEndPoint']['id'] == $myCurrentPoint['id']) and
                                        $mySegmentsAnaliz['segmentWith'] <> 'customer' and // чтоб не шел на Потребителя
                                        $mySegmentsAnaliz['group'] == '' and // чтоб не был определен еще как магистраль (был пустой!)
                                        $mySegmentsAnaliz['segmentID'] <> $myCurrentSegment['segmentID']; // чтоб не был текущим
                                });
                                if (is_object($myCurrentSegment) and count($myCurrentSegment) > 0) {
                                    // продолжение есть

                                    // текущий сегмент и точка
                                    $myCurrentSegment = $myCurrentSegment->first();
                                    $myCurrentPoint = $myCurrentSegment['segmentStartPoint']['id'] == $myCurrentPoint['id'] ? $myCurrentSegment['segmentStartPoint'] : $myCurrentSegment['segmentEndPoint']; // взять такуе же

                                } else {
                                    // продолжения нет - выход из цикла
                                    $myNeedAnaliz2 = false;
                                }
                            }
                        }
                    }
                }
            }
        }

        // отсортировать - по сортировке вершин
        $mySegmentsAnaliz = collect($mySegmentsAnaliz)->sortBy('sort');

        // ----------------------
        // для отладки
        if (false) {
            echo "<br>";
            foreach ($mySegmentsAnaliz as $item) {
                echo "Сегмент: " . $item['segmentName'];
                echo "<br>";
                echo "Группа: " . $item['group'];
                echo "<br>";
                echo "Сортировка: " . $item['sort'];
                echo "<br><br>";
            }
        }
        //dd($myPointsAll_Magistral, $myPointsAll_Otpayka, $myPoints12, $mySegmentsAnaliz, $aclinesegments);

        // проверить на режим, когда нужно вернуть только имена сегментов + магитсраль/отпайка
        if ($getRegimOnlyNameSegment) {
            return $mySegmentsAnaliz;
        }

        // ----------------------
        // сканировать в 2-а прохода - без потребителей и с потребителями, в каждом свои итоги, плюс третья строка всего
        $aclineLenghtNew = [];
        for ($myProhod = 1; $myProhod <= 2; $myProhod++) {

            // сегмент с нужным признаком
            if ($myProhod == 1) {
                $aclinesegmentsNew = $mySegmentsAnaliz->where('segmentWith', '<>', 'customer');
            } else {
                $aclinesegmentsNew = $mySegmentsAnaliz->where('segmentWith', '=', 'customer');
            }

            $aclineLenghtNew[$myProhod] = 0;
            if (count($aclinesegmentsNew) > 0) {
                // сканирую сегменты
                foreach ($aclinesegmentsNew as $segment) {

                    // вставить новую строчку в отчет
                    $myNN++;
                    $myReport [] = [
                        '1' => $myNN,
                        '2' => ($segment['segmentType'] === 701 ? 'воздушный' : ($segment['segmentType'] === 702 ? 'кабельный' : $segment['segmentType'])),
                        '3' => ($segment['group'] <> '' ? "(" . $segment['group'] . ") " : "") . $segment['segmentName'],
                        '4' => $segment['segmentWireMark'],
                        '5' => $segment['segmentLenght'],
                        '6' => $segment['towerItogo'] == 0 ? '' : $segment['towerItogo'],
                        '7' => $segment['towerIronConcrete'] == 0 ? '' : $segment['towerIronConcrete'],
                        '8' => $segment['towerWoodWithAnnex'] == 0 ? '' : $segment['towerWoodWithAnnex'],
                        '9' => $segment['towerWood'] == 0 ? '' : $segment['towerWood'],
                        '10' => $segment['towerMetal'] == 0 ? '' : $segment['towerMetal'],
                        '11' => $segment['towerOther'] == 0 ? '' : $segment['towerOther'],
                    ];

                    // итоговая расчетная длина линии
                    $aclineLenghtNew[$myProhod] += $segment['segmentLenght'];
                }

                // после таблицы
                $myReport [] = [
                    'thisItogo1' => true,
                    '4' => "Всего:",
                    '5' => $aclineLenghtNew[$myProhod],
                    '6' => ($myProhod == 1) ? $aclineTowers : '',
                ];
            }
        }

        // итоговая длина этих двух проходов сегментов
        $aclineLenght = $aclineLenghtNew[1] + $aclineLenghtNew[2];

        // после таблицы - третий итог
        $myReport [] = [
            'thisItogo2' => true,
            '4' => "Всего:",
            '5' => $aclineLenght,
            '6' => $aclineTowers,
        ];

        // возвращаемый параметр
        $myReport = [
            'aclineData' => $myReport,
            'aclineName' => $aclineName,
            'aclineVoltage' => $aclineVoltage,
            'aclineLenght' => $aclineLenght,
            'aclineTowers' => $aclineTowers,
        ];

        // возвращаемый параметр
        return $myReport;
    }

    // ------------------------------------------------------------------
    // открытие вьюшки - паспорт
    public function passport($getID)
    {
        // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
        $thisLineDatas = self::getThisLineDatas($getID);

        $acline = $thisLineDatas['acline'];
        $aclinesegments = $thisLineDatas['aclinesegments'];
        $spans = $thisLineDatas['spans'];
        $towers = $thisLineDatas['towers'];
        $customers = $thisLineDatas['customers'];
        $disconnectors = $thisLineDatas['disconnectors'];
        $dischargers = $thisLineDatas['dischargers'];
        $crossings = $thisLineDatas['crossings'];

        // подсчет опор
        // опоры железобетонные
        $towersIronConcrete = [];
        $towersWood = [];
        $towersWoodWithAnnex = [];
        $towersWoodWithoutAnnex = [];
        if (count($towers) > 0) {
            foreach ($towers as $item) {

                if ($item->fict_tp == 0) {
                    // взять только не фиктивные опоры

                    // заполнить деревянные
                    if ($item->towermaterial_id == 1) {
                        $towersWood [] = $item;
                        if ($item->annex == 'metal' or $item->annex == 'ironConcrete') {
                            // с приставкой
                            $towersWoodWithAnnex [] = $item;
                        } else {
                            // без приставки
                            $towersWoodWithoutAnnex [] = $item;
                        }
                    }
                    // заполнить ж/б
                    if ($item->towermaterial_id == 3) {
                        $towersIronConcrete [] = $item;
                    }
                }
            }
        }

        // подсчет линий
        // протяженность
        $aclineLength = 0;
        // кол-во воздушных пролетов
        $span701 = [];
        // кол-во кабельных участков
        $span702 = [];
        if (count($spans) > 0) {
            foreach ($spans as $item) {
                // суммировать протяженность
                $aclineLength += $item->spanlength;

                // заполнить 701
                if ($item->spantype == 701) {
                    $span701 [] = $item;
                }
                // заполнить 702
                if ($item->spantype == 702) {
                    $span702 [] = $item;
                }
            }
        }
        // средняя  длина пролета
        $aclineLengthAverage = 0;
        if (count($span701) > 0) {
            $aclineLengthAverage = round($aclineLength / count($span701), 1);
        }

        // открыть вюшку
        return view('backend.acline.passport', compact(
            'acline', 'aclinesegments', 'spans', 'towers', 'customers', 'disconnectors', 'dischargers', 'crossings',
            'towersIronConcrete', 'towersWoodWithAnnex', 'towersWoodWithoutAnnex',
            'span701', 'span702',
            'aclineLength', 'aclineLengthAverage'
        ));
    }
}
