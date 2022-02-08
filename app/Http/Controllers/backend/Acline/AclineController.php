<?php

namespace App\Http\Controllers\backend\Acline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

// контроллер модели
class AclineController extends Controller
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
    // открытие вьюшки - вывод списка
    public function index()
    {
        // содержимое загрузить позже Vue

        // открыть вюшку
        return view('backend.acline.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // фильтр - поисковое выражение в имени
        $getFilterBaseVoltage = $request['filterBaseVoltage']; // фильтр - базовое напряжение
        $getFilterAclineStatus = $request['filterAclineStatus']; // фильтр - статус линии
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = Acline::with('aclinesegments')
            ->selectRaw(
                'acline.id, acline.updated_at,
                identifiedobject.name, identifiedobject.address,
                basevoltage.name as basevoltage_name,
                acline_status.name as status_name')
            ->leftJoin('identifiedobject', 'acline.identifiedobject_id', '=', 'identifiedobject.id')
            ->leftJoin('basevoltage', 'identifiedobject.voltage_id', '=', 'basevoltage.id')
            ->leftJoin('acline_status', 'acline.status_id', '=', 'acline_status.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('identifiedobject.name', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getFilterBaseVoltage) and $getFilterBaseVoltage > 0, function ($query) use ($getFilterBaseVoltage) {
                return $query->where('identifiedobject.voltage_id', $getFilterBaseVoltage);
            })
            ->when(isset($getFilterAclineStatus) and $getFilterAclineStatus > 0, function ($query) use ($getFilterAclineStatus) {
                return $query->where('acline.status_id', $getFilterAclineStatus);
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;


        // первый вариант до произвольной сортировки
        // не используется уже !!!
        if (false) {
            $IOs = Identifiedobject
                ::when(isset($getFilterName), function ($query) use ($getFilterName) {
                    return $query->where('name', 'like', '%' . $getFilterName . '%');
                })
                ->when(isset($getFilterBaseVoltage) and $getFilterBaseVoltage > 0, function ($query) use ($getFilterBaseVoltage) {
                    return $query->where('voltage_id', $getFilterBaseVoltage);
                })
                ->pluck('id');

            // получение всех ее строк и связанных данных
            $return = Acline::with([
                'aclinesegments',
                'identifiedobject' => function ($query) {
                    $query->with('basevoltage');
                }])
                ->when($IOs != null and count($IOs) > 0, function ($query) use ($IOs) {
                    return $query->whereIn('identifiedobject_id', $IOs);
                })->latest()->paginate($rowsPerPage);
        }
    }

    // ------------------------------------------------------------------
    // удаление (Vue)
    public function vueDelete(Request $request)
    {
        // переданные параметры через запрос post
        $selectedRows = $request['selectedRows'];
        // преобразовать строчку в массив
        $selectedRows = array_map('intval', explode(',', $selectedRows)); // выделенные строчки

        // сканировать полученный список
        if ($selectedRows and count($selectedRows) > 0) {
            foreach ($selectedRows as $item) {
                $delete = self::destroy($item);
            }
        }
    }

    // ------------------------------------------------------------------
    // удаление строки
    public function destroy($id)
    {
        // "худая" загрузка всех строк всех обьектов без связей для линии ("матрешка" вложенности, только ID-шки) (ч.1)
        $thisLineIDs = self::getThisLineIDs($id);

        Crossing::whereIn('id', $thisLineIDs['crossings'])->delete();
        Disconnector::whereIn('id', $thisLineIDs['disconnectors'])->delete();
        Discharger::whereIn('id', $thisLineIDs['dischargers'])->delete();
        Customer::whereIn('id', $thisLineIDs['customers'])->delete();
        Tower::whereIn('id', $thisLineIDs['towers'])->delete();
        Span::whereIn('id', $thisLineIDs['spans'])->delete();
        Aclinesegment::whereIn('id', $thisLineIDs['aclinesegments'])->delete();
        Identifiedobject::whereIn('id', $thisLineIDs['allIOs'])->delete();
        Acline::where('id', $id)->delete();
    }

    // ------------------------------------------------------------------
    // открытие вьюшки - вывод одной строки
    public function edit($id)
    {
        // контент
        if ($id) {
            $content = Acline::find($id);
        } else {
            $content = new Acline;
        }

        // справочники и другие дополнительные сведения

        // открыть вюшку
        return view('backend.acline.edit', compact('content'));
    }

    // ------------------------------------------------------------------
    // загрузка данных (Vue)
    public function vueLoad(Request $request)
    {
        // переданные параметры через запрос post
        $getModelID = $request['modelID'];

        $thisLineDatas = null;
        if (isset($getModelID) > 0) {

            // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
            $thisLineDatas = self::getThisLineDatas($getModelID);

            // для сегментов сделать такой же анализ магистраль/отпайка, как и в отчете-2
            if ($thisLineDatas) {

                // отчет-2 (вся логика)
                $mySegmentsAnaliz = AclineReportController::report2_content($thisLineDatas, true);

                // дописать новым полем
                if ($mySegmentsAnaliz) {
                    foreach ($thisLineDatas['aclinesegments'] as $key => $segmentOld) {
                        foreach ($mySegmentsAnaliz as $segmentNew) {
                            if ($segmentOld->id == $segmentNew['segmentID']) {
                                $thisLineDatas['aclinesegments'][$key]->setAttribute('segmentAnaliz', $segmentNew);
                            }
                        }
                    }
                }
            }
        }

        // возвращаемый параметр
        return $thisLineDatas;
    }

    // ------------------------------------------------------------------
    // сохранение данных (Vue) - вкладка основное
    public function vueSave(Request $request)
    {
        // переданные параметры через запрос post
        $getModelID = $request['modelID'];
        $getModelData = json_decode($request['modelData'], true);

        // линия
        if (isset($getModelID) and $getModelID > 0) {
            $modelSave = Acline::find($getModelID);
            if ($modelSave) {
                $modelIOSave = Identifiedobject::find($modelSave->identifiedobject_id);
            } else {
                $modelIOSave = new Identifiedobject;
            }
        } else {
            // это первое сохранение
            $modelSave = new Acline;
            $modelIOSave = new Identifiedobject;
        }

        // подготовка полей для сохранение в основной таблице
        // проверка, есть ли данные в полученном массиве
        $myFields = ['status_id'];
        foreach ($myFields as $item) {
            if (isset($getModelData[$item])) {
                $modelSave->$item = $getModelData[$item];
            }
        }

        // подготовка полей для сохранение в IO
        // проверка, есть ли данные в полученном массиве
        $myFields = ['name', 'voltage_id'];
        foreach ($myFields as $item) {
            if (isset($getModelData[$item])) {
                $modelIOSave->$item = $getModelData[$item];
            }
        }

        // сохранить в IO
        $modelIOSave->save();
        // сохранить в основной таблице через родителя IO, чтобы сразу получить id
        $modelIOSave->acline()->save($modelSave);
        // принудительно записать время обновления
        $modelSave->touch();

        // узнать привоенный ID, чтобы вернуть обратно во вьшку
        $aclineID = $modelSave->id;

        // возвращаемый параметр
        return $aclineID;
    }

    // ------------------------------------------------------------------
    // сохранение данных со второстепенных вкладок (Vue) - сегменты, пролеты, опоры, потребители, разьединители, разрядники
    public function vueSaveOther(Request $request)
    {
        // переданные параметры через запрос post
        $getModelName = $request['modelName'];
        $getModelData = json_decode($request['modelData'], true);
        $getAclineID = $request['aclineID'];

        switch ($getModelName) {
            case 'Segment':
                // сегменты
                break;
            case 'Span':
                // пролеты
                break;
            case 'Tower':
                // опоры
                $tower = Tower::where('id', $getModelData['id'])->get()->first();
                if ($tower) {
                    $tower->setName($getAclineID, $getModelData['name']);
                }
                break;
            case 'Customer':
                // потребители
                $customer = Customer::with('identifiedobject')->where('id', $getModelData['id'])->get()->first();
                if ($customer) {
                    $customerIO = Identifiedobject::where('id', $customer->identifiedobject_id)->get()->first();
                    if ($customerIO) {
                        $customerIO->address = $getModelData['name'];
                        $customerIO->save();
                    }
                }
                break;
            case 'Disconnector':
                // разьединители
                break;
            case 'Discharger':
                // разрядники
                break;
            case 'Crossing':
                // пересечение местности
                break;
        }
    }

    // ------------------------------------------------------------------
    // "худая" загрузка всех строк всех обьектов без связей для линии ("матрешка" вложенности, только ID-шки) (ч.1)
    static function getThisLineIDs($getAclineID)
    {
        $aclineAll = Acline::select(['id', 'identifiedobject_id'])->where('id', $getAclineID)->get();
        $acline = $aclineAll->pluck('id');
        $aclineIOs = $aclineAll->pluck('identifiedobject_id');

        if ($acline) {

            $aclinesegmentsAll = Aclinesegment::select(['id', 'identifiedobject_id'])->where('acline_id', $getAclineID)->get();
            $aclinesegments = $aclinesegmentsAll->pluck('id');
            $aclinesegmentIOs = $aclinesegmentsAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            $spansAll = Span::select(['id', 'identifiedobject_id', 'startIO_id', 'endIO_id'])->whereIn('aclinesegment_id', $aclinesegments)->get();
            $spans = $spansAll->pluck('id');
            $spanIOs = $spansAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            // вершины пролетов (start + end)
            $spanStartIOs = $spansAll->whereNotNull('startIO_id')->pluck('startIO_id');
            $spanEndIOs = $spansAll->whereNotNull('endIO_id')->pluck('endIO_id');
            $spanStartEndIOs = $spanStartIOs->merge($spanEndIOs);

            $towersAll = Tower::select(['id', 'identifiedobject_id'])->whereIn('identifiedobject_id', $spanStartEndIOs)->get();
            $towers = $towersAll->pluck('id');
            $towerIOs = $towersAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            $customersAll = Customer::select(['id', 'identifiedobject_id'])->whereIn('identifiedobject_id', $spanStartEndIOs)->get();
            $customers = $customersAll->pluck('id');
            $customerIOs = $customersAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            // где могут устанавливаться разьединители, реклоузеры, выключатели напряжения, разрядники, ОПН, заземление
            $towersCustomersIOs = $towerIOs->merge($customerIOs);

            $disconnectorsAll = Disconnector::select(['id', 'identifiedobject_id'])->whereIn('startIO_id', $towersCustomersIOs)->get();
            $disconnectors = $disconnectorsAll->pluck('id');
            $disconnectorIOs = $disconnectorsAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            $dischargersAll = Discharger::select(['id', 'identifiedobject_id'])->whereIn('startIO_id', $towersCustomersIOs)->get();
            $dischargers = $dischargersAll->pluck('id');
            $dischargerIOs = $dischargersAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            $crossingsAll = Crossing::select(['id', 'identifiedobject_id'])->whereIn('span_id', $spans)->get();
            $crossings = $crossingsAll->pluck('id');
            $crossingIOs = $crossingsAll->whereNotNull('identifiedobject_id')->pluck('identifiedobject_id');

            $allIOs = $aclineIOs;
            if (count($aclinesegmentIOs) > 0) $allIOs = $allIOs->merge($aclinesegmentIOs);
            if (count($spanIOs) > 0) $allIOs = $allIOs->merge($spanIOs);
            if (count($towerIOs) > 0) $allIOs = $allIOs->merge($towerIOs);
            if (count($customerIOs) > 0) $allIOs = $allIOs->merge($customerIOs);
            if (count($disconnectorIOs) > 0) $allIOs = $allIOs->merge($disconnectorIOs);
            if (count($dischargerIOs) > 0) $allIOs = $allIOs->merge($dischargerIOs);
            if (count($crossingIOs) > 0) $allIOs = $allIOs->merge($crossingIOs);
            //dd($getAclineID, $aclineIOs, $aclinesegmentIOs, $spanIOs, $towerIOs, $customerIOs, $allIOs);

            // убрать дубликаты
            //$allIOs->duplicates(); // оператор вытаскивает дубликаты, а не исключает их

            // возвращаемый параметр
            $thisLineIDs = [
                'acline' => $getAclineID,
                'aclinesegments' => $aclinesegments,
                'aclinesegmentIOs' => $aclinesegmentIOs,
                'spans' => $spans,
                'spanIOs' => $spanIOs,
                'spanStartEndIOs' => $spanStartEndIOs,
                'towers' => $towers,
                'towerIOs' => $towerIOs,
                'customers' => $customers,
                'customerIOs' => $customerIOs,
                'disconnectors' => $disconnectors,
                'disconnectorIOs' => $disconnectorIOs,
                'dischargers' => $dischargers,
                'dischargerIOs' => $dischargerIOs,
                'crossings' => $crossings,
                'crossingIOs' => $crossingIOs,
                'allIOs' => $allIOs,
            ];
        }

        // возвращаемый параметр
        return $thisLineIDs;
    }

    // ------------------------------------------------------------------
    // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
    static function getThisLineDatas($getAclineID = null)
    {
        // контент
        if ($getAclineID) {

            // "худая" загрузка всех строк всех обьектов без связей для линии ("матрешка" вложенности, только ID-шки) (ч.1)
            $thisLineIDs = self::getThisLineIDs($getAclineID);

            $acline = Acline
                ::with('identifiedobject', 'aclinesegments', 'connector', 'aclinestatus')
                ->where('id', $getAclineID)->get()->first();

            if ($thisLineIDs['aclinesegments']) {
                $aclinesegments = Aclinesegment
                    ::with('identifiedobject', 'acline', 'spans', 'wiremark', 'layingcondition')
                    ->whereIn('id', $thisLineIDs['aclinesegments'])->get();
            } else {
                $aclinesegments = new Aclinesegment;
            }

            if ($thisLineIDs['spans']) {
                $spans = Span
                    ::with('identifiedobject', 'aclinesegment', 'startIO', 'endIO', 'crossings')
                    ->whereIn('id', $thisLineIDs['spans'])->get();
            } else {
                $spans = new Span;
            }

            if ($thisLineIDs['towers']) {
                $towers = Tower
                    ::with('identifiedobject', 'towermaterial', 'towerkind', 'towerconstruction', 'towerinfo', 'connectivitycode')
                    ->whereIn('id', $thisLineIDs['towers'])->get();

                // вставка имен опор конкретно в этой линии (по-старому и по-новому варианту)
                if ($towers) {
                    $n = count($towers);
                    if ($n > 0) {
                        for ($i = 0; $i < $n; $i++) {
                            $towers[$i]->viewName = $towers[$i]->getName($getAclineID);
                        }
                    }
                }
            } else {
                $towers = new Tower;
            }

            if ($thisLineIDs['customers']) {
                $customers = Customer
                    ::with('identifiedobject')
                    ->whereIn('id', $thisLineIDs['customers'])->get();
            } else {
                $customers = new Customer;
            }

            if ($thisLineIDs['disconnectors']) {
                $disconnectors = Disconnector
                    ::with('identifiedobject', 'startIO', 'span', 'disconnectorinfo')
                    ->whereIn('id', $thisLineIDs['disconnectors'])->get();
            } else {
                $disconnectors = new Disconnector;
            }

            if ($thisLineIDs['dischargers']) {
                $dischargers = Discharger
                    ::with('identifiedobject', 'startIO', 'span', 'dischargerinfo')
                    ->whereIn('id', $thisLineIDs['dischargers'])->get();
            } else {
                $dischargers = new Discharger;
            }

            if ($thisLineIDs['crossings']) {
                $crossings = Crossing
                    ::with('identifiedobject', 'span', 'crossingtype')
                    ->whereIn('id', $thisLineIDs['crossings'])->get();
            } else {
                $crossings = new Crossing;
            }

        } else {
            $acline = new Acline;
            $aclinesegments = new Aclinesegment;
            $spans = new Span;
            $towers = new Tower;
            $customers = new Customer;
            $disconnectors = new Disconnector;
            $dischargers = new Discharger;
            $crossings = new Crossing;
        }

        //dd($dischargers, $thisLineIDs['dischargers']);
        //dd($acline, $aclinesegments, $spans, $towers, $customers, $disconnectors, $dischargers, $crossings);

        // возвращаемый параметр
        $thisLineDatas = [
            'acline' => $acline,
            'aclinesegments' => $aclinesegments,
            'spans' => $spans,
            'towers' => $towers,
            'customers' => $customers,
            'disconnectors' => $disconnectors,
            'dischargers' => $dischargers,
            'crossings' => $crossings,
        ];

        // возвращаемый параметр
        return $thisLineDatas;
    }
}
