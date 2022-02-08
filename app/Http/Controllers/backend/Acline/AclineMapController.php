<?php

namespace App\Http\Controllers\backend\Acline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

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
class AclineMapController extends AclineController
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
    // открытие вьюшки - загрузка карты
    public function mapEdit($getAclineID = null, $regim = null)
    {
        if ($regim === null) {
            // по-старому все через Яндекс-карту

            // "жирная" загрузка данных линии (используется в карте и паспорте) (ч.2 - опирается на ч.1)
            $thisLineDatas = self::getThisLineDatas($getAclineID);

            $acline = $thisLineDatas['acline'];
            $aclinesegments = $thisLineDatas['aclinesegments'];
            $spans = $thisLineDatas['spans'];
            $towers = $thisLineDatas['towers'];
            $customers = $thisLineDatas['customers'];
            $disconnectors = $thisLineDatas['disconnectors'];
            $dischargers = $thisLineDatas['dischargers'];
            $crossings = $thisLineDatas['crossings'];

            // обновить черновик
            // для совместимости со старыми сохраненными версиями в JSON-массвие и актуальности данных - дополнить черновик новыми полями и более свежей информацией
            // например, не было полей isActive, isDoubleAcline, aclinesObject
            $myDraftOld = json_decode($acline->map_json, true);
            $myDraftNew = self::mapDraftUpdate($myDraftOld, $getAclineID);
            // записать обратно обновленный черновик
            $acline->map_json = json_encode($myDraftNew);
        }

        // справочники
        $aclinesegmentinfos = Aclinesegmentinfo::where('status', 1)->orderBy('assetinfokey', 'ASC')->get(); // марки проводов отсортировал, чтоб легче было найти в списке
        $basevoltages = BaseVoltage::where('status', 1)->get();
        $crossingtypes = Crossingtype::where('status', 1)->get(); // !!! пока нигде не используется
        $dischargerinfos = Dischargerinfo::where('status', 1)->orderBy('ASSETINFOKEY', 'ASC')->get(); // марки разрядников отсортировал, чтоб легче было найти в списке
        $disconnectorinfos = DisconnectorInfo::where('status', 1)->orderBy('ASSETINFOKEY', 'ASC')->get(); // марки разьединителей отсортировал, чтоб легче было найти в списке
        $layingconditionkinds = Layingconditionkind::where('status', 1)->get();
        $materialkinds = Materialkind::where('status', 1)->get(); // !!! пока нигде не используется
        $towerkinds = Towerkind::where('status', 1)->get();
        $towerinfos = Towerinfo::where('status', 1)->orderBy('mark', 'ASC')->get(); // марки опор, чтоб легче было найти в списке
        $towerconstructionkinds = Towerconstructionkind::where('status', 1)->get();
        $towermaterials = Towermaterial::where('status', 1)->get();
        $aclinestatus = AclineStatus::all();

        if ($regim === null) {
            // по-старому все через Яндекс-карту

            // для связи
            $terminals = Terminal::with('identifiedobject')->get();
            $busbarsection = Busbarsection::with('identifiedobject')->get();

            // получить список ближайших обьектов по дистанции
            $setting_map_center = $this->commonService->getAdmminSetting('setting_map_center'); // здесь от Режа для первичной загрузки
            $substations = $this->yandexMapService->getNearObjectsOnDistance('Substation', $setting_map_center);

        }

        // права Пользователя
        $userHasEditRights = (Auth::user()->isVendor() or Auth::user()->isAdmin() or Auth::user()->isManager() or Auth::user()->isOperator()) ? 1 : 0;

        // открыть вюшку
        if ($regim === 'svg') {
            // по-новому через svg

            $allSpravs = [
                'aclinesegmentinfos' => $aclinesegmentinfos,
                'basevoltages' => $basevoltages,
                'crossingtypes' => $crossingtypes,
                'dischargerinfos' => $dischargerinfos,
                'disconnectorinfos' => $disconnectorinfos,
                'layingconditionkinds' => $layingconditionkinds,
                'materialkinds' => $materialkinds,
                'towerkinds' => $towerkinds,
                'towerinfos' => $towerinfos,
                'towerconstructionkinds' => $towerconstructionkinds,
                'towermaterials' => $towermaterials,
                'aclinestatus' => $aclinestatus,
                'userHasEditRights' => $userHasEditRights,
            ];

            return view('backend.acline.editSvg', compact('allSpravs'));
        } else {
            // по-старому все через Яндекс-карту

            return view('backend.acline.map.main', compact(
                'acline', 'aclinesegments', 'spans', 'towers', 'customers', 'disconnectors', 'dischargers', 'crossings',
                'aclinesegmentinfos', 'basevoltages', 'crossingtypes', 'dischargerinfos', 'disconnectorinfos', 'layingconditionkinds', 'materialkinds', 'towerkinds', 'towerinfos', 'towerconstructionkinds', 'towermaterials',
                'substations', 'terminals', 'busbarsection',
                'aclinestatus',
                'userHasEditRights'));
        }
    }

    // ------------------------------------------------------------------
    // обновить черновик
    public function mapDraftUpdate($getDraft, $getAclineID)
    {
        // обновить черновик - ч.1 - добавить поля, если их еще не было
        $getDraft = self::mapDraftUpdateFieldsAdd($getDraft);
        // обновить черновик - ч.2 - обновить актуальной информацией
        $getDraft = self::mapDraftUpdateDataAdd($getDraft, $getAclineID);

        // возвращаемый параметр
        return $getDraft;
    }

    // ------------------------------------------------------------------
    // обновить черновик - ч.1 - добавить поля, если их еще не было
    public function mapDraftUpdateFieldsAdd($getDraft)
    {
        // сканировать переданный массив по строкам
        if ($getDraft != null and count($getDraft) > 0) {
            foreach ($getDraft as $key => $item) {

                // список полей обьекта на карте (на фронте в mmObject)
                $myArrObjectOne = self::mapObjectOne();
                // сканировать поля обьекта
                foreach ($myArrObjectOne as $objectOneField) {

                    if (!array_key_exists($objectOneField, $item)) {
                        // еще нету
                        $getDraft[$key][$objectOneField] = null;
                    }
                }
            }
        }

        // возвращаемый параметр
        return $getDraft;
    }

    // ------------------------------------------------------------------
    // обновить черновик - ч.2 - обновить актуальной информацией
    public function mapDraftUpdateDataAdd($getDraft, $getAclineID)
    {
        // сканировать переданный массив по строкам
        if ($getDraft != null and count($getDraft) > 0) {
            foreach ($getDraft as $key => $item) {

                $myDbID = (int)$item['dbID'];
                $myDbIOID = (int)$item['dbIOID'];

                if ($myDbID > 0 and $myDbIOID > 0 and $item['type'] === 'tower' and $item['deleted'] == false) {
                    // это опора

                    // данные этой опоры в БД
                    $tower = Tower::where('id', $myDbID)->get()->first();

                    if ($tower) {
                        // обновить данные

                        $getDraft[$key]['dbIOID'] = $tower->identifiedobject_id;
                        $getDraft[$key]['dbConnectivitycodeID'] = $tower->connectivitycode_id;
                        $getDraft[$key]['name'] = $tower->getName($getAclineID); // IOObject['name'];
                        $getDraft[$key]['localName'] = $tower->getName($getAclineID); //IOObject['localname'];
                        $getDraft[$key]['address'] = $tower->IOObject['address'];
                        $getDraft[$key]['viewName'] = $tower->getName($getAclineID);
                        $getDraft[$key]['hint'] = $tower->getName($getAclineID);
                        $getDraft[$key]['lat'] = $tower->IOObject['lat'];
                        $getDraft[$key]['long'] = $tower->IOObject['long'];
                        $getDraft[$key]['towerMaterial'] = $tower->towermaterial_id;
                        $getDraft[$key]['towerKind'] = $tower->towerkind_id;
                        $getDraft[$key]['towerInfo'] = $tower->towerinfo_id;
                        $getDraft[$key]['towerConstruction'] = $tower->towerconstruction_id;
                        $getDraft[$key]['propN'] = $tower->propn;
                        $getDraft[$key]['guy'] = $tower->guy;
                        $getDraft[$key]['strut'] = $tower->strut;
                        $getDraft[$key]['strutN'] = $tower->strutn;
                        $getDraft[$key]['annex'] = $tower->annex;
                        $getDraft[$key]['eqGrounding'] = $tower->eqgrounding;
                        $getDraft[$key]['eqOtherLine'] = $tower->eqotherline;
                        $getDraft[$key]['eqCommLine'] = $tower->eqcommline;
                        $getDraft[$key]['eqLamp'] = $tower->eqlamp;
                        $getDraft[$key]['eqAdapter'] = $tower->eqadapter;
                        $getDraft[$key]['eqAccident'] = $tower->eqaccident;
                        $getDraft[$key]['eqNoUp'] = $tower->eqnoup;
                        $getDraft[$key]['isDoubleAcline'] = ($tower->aclinesObject['count'] > 1) ? true : false;
                        $getDraft[$key]['aclinesObject'] = $tower->aclinesObject;

                        // фото не заменить, а пополнить
                        // фото, которые были
                        $myPhotosOld = $getDraft[$key]['photos'];
                        // фото, которые хочу взять для обновления
                        $myPhotosNew = ($tower->photos !== null and $tower->photos !== '' and $tower->photos !== '[]') ? json_decode($tower->photos) : [];
                        // неповторяющиеся фото из двух массивов
                        $myPhotosUnique = array_unique(array_merge($myPhotosOld, $myPhotosNew));
                        // вставить их обратно в черновик
                        $getDraft[$key]['photos'] = $myPhotosUnique;
                    }
                }
            }
        }

        // возвращаемый параметр
        return $getDraft;
    }

    // ------------------------------------------------------------------
    // список полей обьекта на карте (на фронте в mmObject)
    // !!! список полей должен быть одинаков с fun_mapObjectAdd/function ObjectOne
    public function mapObjectOne()
    {
        // возвращаемый параметр
        return
            [
                'deleted', 'mapID', 'dbID', 'dbIOID', 'dbConnectivitycodeID', 'mapType', 'type',
                'name', 'localName', 'address', 'viewName', 'hint',
                'lat', 'long', 'startMapID', 'endMapID', 'points', 'lineToCustomer',
                'towerMaterial', 'towerKind', 'towerInfo', 'towerConstruction',
                'propN', 'guy', 'strut', 'strutN', 'annex',
                'wireMark', 'layingCondition', 'wireS', 'wireN', 'wireLength', 'wirePhaseN', 'cabelsN', 'gabarit',
                'eqDisconnectorStart', 'eqDisconnectorStartInfo', 'eqDisconnectorEnd', 'eqDisconnectorEndInfo',
                'eqReklouzerStart', 'eqReklouzerStartInfo', 'eqReklouzerEnd', 'eqReklouzerEndInfo',
                'eqVNaStart', 'eqVNaStartInfo', 'eqVNaEnd', 'eqVNaEndInfo',
                'eqDischarger', 'eqDischargerInfo', 'eqOPN', 'eqOPNInfo', 'eqGrounding',
                'eqOtherLine', 'eqCommLine', 'eqLamp', 'eqAdapter', 'eqAccident', 'eqNoUp',
                'photos', 'spanLength', 'isActive',
                'isDoubleAcline', 'aclinesObject'
            ];
    }

    // ------------------------------------------------------------------
    // сохранение карты
    public function mapUpdate(Request $request)
    {
        // полученные переменные через AJAX
        $getAclineID = (int)$request['aclineID'];
        $getAclineName = $request['aclineName'];
        $getAclineNameDefault = $request['aclineNameDefault'];
        $getAclineStatus = $request['aclineStatus'];
        $getAclineBaseVoltage = $request['aclineBaseVoltage']; // для всех элементов этой ЛЭП базовое напряжение
        $getSegments = json_decode($request['mmSegments'], true);
        $getObjects = json_decode($request['mmObjects'], true);  // первоисточник

        // сохранение обьектов линии
        $myArrSaveIDs = [];

        // линия
        if ($getAclineID and $getAclineID > 0) {
            $aclineSave = Acline::find($getAclineID);
            if ($aclineSave) {
                $IOaclineSave = Identifiedobject::find($aclineSave->identifiedobject_id);
            } else {
                $IOaclineSave = new Identifiedobject;
            }
        } else {
            // это первое сохранение
            $aclineSave = new Acline;
            $IOaclineSave = new Identifiedobject;
        }

        $aclineSave->status_id = $getAclineStatus;
        $aclineSave->map_json = json_encode($getObjects);
        $aclineSave->save();

        // дописать к имени линии id, если там было имя-по умолчанию
        $getAclineName = ($getAclineName == $getAclineNameDefault) ? $getAclineName . ' - ' . $aclineSave->id : $getAclineName;

        $IOaclineSave->name = $getAclineName;
        $IOaclineSave->voltage_id = $getAclineBaseVoltage;
        $IOaclineSave->save();
        // сохранить через родителя IO, чтобы сразу получить id
        $IOaclineSave->acline()->save($aclineSave);
        // узнать привоенный ID, чтобы вернуть обратно во вьшку
        $getAclineID = $aclineSave->id;
        // принудительно записать время обновления
        $aclineSave->touch();

        // "худая" загрузка всех строк всех обьектов без связей для линии ("матрешка" вложенности, только ID-шки) (ч.1)
        $thisLineIDs = self::getThisLineIDs($getAclineID);

        // массив с ID обьектов карты
        $myMapIDs = [
            'customers' => [],
            'substations' => [],
            'spans' => [],
            'towers' => [],
            'dischargers' => [],
            'OPNs' => [],
            'disconnectors' => [],
            'reklouzers' => [],
            'VNas' => [],
        ];
        if (is_array($getObjects)) {
            foreach ($getObjects as $item) {

                if ($item['dbID'] <> null and $item['deleted'] == false) {

                    if ($item['type'] == 'customer') {
                        // потребители
                        $myMapIDs['customers'] [] = (int)$item['dbID'];
                    }
                    if ($item['type'] == 'substation') {
                        // фиктивные ТП в опорах
                        $myMapIDs['substations'] [] = (int)$item['dbID'];
                    }
                    if ($item['type'] == 'tower') {
                        // опоры
                        $myMapIDs['towers'] [] = (int)$item['dbID'];

                        // разрядники
                        if ($item['eqDischarger'] == 1) {
                            $myMapIDs['dischargers'] [] = (int)$item['dbIOID'];
                        }
                        // ОПН
                        if ($item['eqOPN'] == 1) {
                            $myMapIDs['OPNs'] [] = (int)$item['dbIOID'];
                        }
                    }

                    if ($item['mapType'] == 'polyline') {

                        // пролеты/участки
                        $myMapIDs['spans'] [] = (int)$item['dbID'];

                        // разьединители
                        if ($item['eqDisconnectorStart'] == 1) {
                            $myMapIDs['disconnectors'] [] = (int)$getObjects[$item['startMapID']]['dbIOID'];
                        }
                        if ($item['eqDisconnectorEnd'] == 1) {
                            $myMapIDs['disconnectors'] [] = (int)$getObjects[$item['endMapID']]['dbIOID'];
                        }

                        // реклоузеры
                        if ($item['eqReklouzerStart'] == 1) {
                            $myMapIDs['reklouzers'] [] = (int)$getObjects[$item['startMapID']]['dbIOID'];
                        }
                        if ($item['eqReklouzerEnd'] == 1) {
                            $myMapIDs['reklouzers'] [] = (int)$getObjects[$item['endMapID']]['dbIOID'];
                        }

                        // выключатель нагрузки
                        if ($item['eqVNaStart'] == 1) {
                            $myMapIDs['VNas'] [] = (int)$getObjects[$item['startMapID']]['dbIOID'];
                        }
                        if ($item['eqVNaEnd'] == 1) {
                            $myMapIDs['VNas'] [] = (int)$getObjects[$item['endMapID']]['dbIOID'];
                        }
                    }
                }
            }
        }

        // удалить те обьекты из базы, которых уже нет на карте (даже если $getObjects пустой - Пользователь нажал очистить все! проверки, что массив > 0 не нужно!)
        // пролеты/участки
        $deleteForIO = Span::select('identifiedobject_id')->whereIn('id', $thisLineIDs['spans'])->whereNOTIn('id', $myMapIDs['spans'])->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Span::whereIn('id', $thisLineIDs['spans'])->whereNOTIn('id', $myMapIDs['spans'])->delete();

        // опоры
        $deleteForIO = Tower::select('identifiedobject_id')->whereIn('id', $thisLineIDs['towers'])->where('fict_tp', 0)->whereNOTIn('id', $myMapIDs['towers'])->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Tower::whereIn('id', $thisLineIDs['towers'])->where('fict_tp', 0)->whereNOTIn('id', $myMapIDs['towers'])->delete();

        // фиктивные ТП в опорах
        $deleteForIO = Tower::select('identifiedobject_id')->whereIn('id', $thisLineIDs['towers'])->where('fict_tp', 1)->whereNOTIn('id', $myMapIDs['substations'])->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Tower::whereIn('id', $thisLineIDs['towers'])->where('fict_tp', 1)->whereNOTIn('id', $myMapIDs['substations'])->delete();

        // потребители
        $deleteForIO = Customer::select('identifiedobject_id')->whereIn('id', $thisLineIDs['customers'])->whereNOTIn('id', $myMapIDs['customers'])->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Customer::whereIn('id', $thisLineIDs['customers'])->whereNOTIn('id', $myMapIDs['customers'])->delete();

        // разьединители
        $deleteForIO = Disconnector::select('identifiedobject_id')->whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['disconnectors'])->where('type', 1)->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Disconnector::whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['disconnectors'])->where('type', 1)->delete();

        // реклоузеры
        $deleteForIO = Disconnector::select('identifiedobject_id')->whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['reklouzers'])->where('type', 2)->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Disconnector::whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['reklouzers'])->where('type', 2)->delete();

        // выключатель нагрузки
        $deleteForIO = Disconnector::select('identifiedobject_id')->whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['VNas'])->where('type', 3)->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Disconnector::whereIn('id', $thisLineIDs['disconnectors'])->whereNOTIn('startIO_id', $myMapIDs['VNas'])->where('type', 3)->delete();

        // разрядники
        $deleteForIO = Discharger::select('identifiedobject_id')->whereIn('id', $thisLineIDs['dischargers'])->whereNOTIn('startIO_id', $myMapIDs['dischargers'])->where('type', 1)->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Discharger::whereIn('id', $thisLineIDs['dischargers'])->whereNOTIn('startIO_id', $myMapIDs['dischargers'])->where('type', 1)->delete();

        // ОПН
        $deleteForIO = Discharger::select('identifiedobject_id')->whereIn('id', $thisLineIDs['dischargers'])->whereNOTIn('startIO_id', $myMapIDs['OPNs'])->where('type', 2)->get();
        Identifiedobject::whereIn('id', $deleteForIO)->delete();
        Discharger::whereIn('id', $thisLineIDs['dischargers'])->whereNOTIn('startIO_id', $myMapIDs['OPNs'])->where('type', 2)->delete();

        // сегмент
        // удаление всех старых сегментов в бд для данной линии, потому что они заново динамически наработались
        Aclinesegment::where('acline_id', $getAclineID)->delete();
        // сохранение сегментов
        if (isset($getSegments) and count($getSegments) > 0) {
            foreach ($getSegments as $key => $segment) {

                $segmentsSave = new Aclinesegment;
                $segmentsSave->acline_id = $getAclineID;
                $segmentsSave->wiremark_id = $getObjects[$segment[0]]['wireMark'];
                $segmentsSave->layingcondition_id = $getObjects[$segment[0]]['layingCondition'];
                $segmentsSave->wires = $getObjects[$segment[0]]['wireS'];
                $segmentsSave->wiren = $getObjects[$segment[0]]['wireN'];
                $segmentsSave->wirelength = $getObjects[$segment[0]]['wireLength'];
                $segmentsSave->wirephasen = $getObjects[$segment[0]]['wirePhaseN'];
                $segmentsSave->cabelsn = $getObjects[$segment[0]]['cabelsN'];

                $segmentsSave->save();

                // сканировать пролеты этого сегмента
                foreach ($segment as $key => $segmentSpan) {

                    // проверить, передали ли dbID
                    $myDbID = $getObjects[$segmentSpan]['dbID'];
                    if ($myDbID != null) {
                        // dbID не пустое
                        // проверить, есть ли уже в базе
                        $spanSave = Span::find($myDbID);
                        if ($spanSave) {
                            // в базе есть
                            $IOspans = Identifiedobject::where('id', $spanSave['identifiedobject_id'])->get()->first();
                            if (!$IOspans) {
                                $IOspans = new Identifiedobject;
                            }
                        } else {
                            // в базе еще нет
                            $spanSave = new Span;
                            $IOspans = new Identifiedobject;
                        }
                    } else {
                        // dbID пустое
                        $spanSave = new Span;
                        $IOspans = new Identifiedobject;
                    }
                    $spanSave->aclinesegment_id = $segmentsSave->id;
                    $spanSave->spantype = $getObjects[$segmentSpan]['type'];
                    $spanSave->spanlength = $getObjects[$segmentSpan]['spanLength'];
                    $spanSave->gabarit = $getObjects[$segmentSpan]['gabarit'];
                    $spanSave->points = (isset($getObjects[$segmentSpan]['points']) and !is_null($getObjects[$segmentSpan]['points'])) ? json_encode($getObjects[$segmentSpan]['points']) : '';

                    // сохранить в IO
                    $IOspans->voltage_id = $getAclineBaseVoltage;
                    $IOspans->name = $getObjects[$segmentSpan]['name'];;
                    $IOspans->save();
                    // сохранить через родителя IO, чтобы сразу получить id
                    $IOspans->span()->save($spanSave);

                    // массив, в котором буду запоминать id точки с карты и номер присвоенный в БД при сохранении
                    $myArrSaveIDs[] = [
                        'mapID' => $segmentSpan,
                        'dbID' => $spanSave->id,
                        'dbIOID' => $IOspans->id,
                    ];

                    // вершины этого пролета/участка - опоры, ТП или Потребители
                    for ($pointN = 1; $pointN <= 2; $pointN++) {
                        switch ($pointN) {
                            case 1:
                                $point = $getObjects[$getObjects[$segmentSpan]['startMapID']];
                                $pointName = 'Start';
                                break;
                            case 2:
                                $point = $getObjects[$getObjects[$segmentSpan]['endMapID']];
                                $pointName = 'End';
                                break;
                        }

                        // проверка, сохраняли ли уже эту точку
                        $needSavePoint = true;
                        $currentPoint = [];
                        foreach ($myArrSaveIDs as $myArrSaveIDsItem) {
                            if ($myArrSaveIDsItem['mapID'] == $point['mapID']) {
                                $needSavePoint = false;
                                $currentPoint['DbID'] = $myArrSaveIDsItem['mapID'];
                                $currentPoint['dbIOID'] = $myArrSaveIDsItem['dbIOID'];
                            }
                        }

                        if ($needSavePoint) {
                            if ($point['type'] == 'tower' or $point['type'] == 'substation') {
                                // опора или фиктивная ТП

                                // проверить, передали ли dbID
                                if ($point['dbID'] != null) {
                                    // dbID не пустое
                                    // проверить, есть ли уже в базе
                                    $towerSave = Tower::find($point['dbID']);
                                    if ($towerSave) {
                                        // в базе есть
                                        $IOtowerSave = Identifiedobject::where('id', $towerSave['identifiedobject_id'])->get()->first();
                                        if (!$IOtowerSave) {
                                            $IOtowerSave = new Identifiedobject;
                                        }
                                    } else {
                                        // в базе еще нет
                                        $towerSave = new Tower;
                                        $IOtowerSave = new Identifiedobject;
                                    }
                                } else {
                                    // dbID пустое
                                    $towerSave = new Tower;
                                    $IOtowerSave = new Identifiedobject;
                                }

                                $towerSave->fict_tp = ($point['type'] == 'substation') ? 1 : 0;
                                $towerSave->connectivitycode_id = $point['dbConnectivitycodeID'];

                                $towerSave->towermaterial_id = $point['towerMaterial'];
                                $towerSave->towerkind_id = $point['towerKind'];
                                $towerSave->towerinfo_id = $point['towerInfo'];
                                $towerSave->towerconstructionkind_id = $point['towerConstruction'];

                                $towerSave->propn = $point['propN'];
                                $towerSave->guy = $point['guy'];
                                $towerSave->strut = $point['strut'];
                                $towerSave->strutn = $point['strutN'];
                                $towerSave->annex = $point['annex'];

                                $towerSave->eqgrounding = $point['eqGrounding'];

                                $towerSave->eqotherline = $point['eqOtherLine'];
                                $towerSave->eqcommline = $point['eqCommLine'];
                                $towerSave->eqlamp = $point['eqLamp'];
                                $towerSave->eqadapter = $point['eqAdapter'];
                                $towerSave->eqaccident = $point['eqAccident'];
                                $towerSave->eqnoup = $point['eqNoUp'];

                                $towerSave->photos = (isset($point['photos']) and !is_null($point['photos'])) ? json_encode($point['photos']) : '';

                                // сохранить в IO

                                // сохранение имени по-старому
                                //$IOtowerSave->name = $point['name'];
                                //$IOtowerSave->localname = $point['localName'];

                                $IOtowerSave->address = $point['address'];
                                $IOtowerSave->lat = $point['lat'];
                                $IOtowerSave->long = $point['long'];
                                $IOtowerSave->voltage_id = $getAclineBaseVoltage;
                                $IOtowerSave->save();
                                // сохранить через родителя IO, чтобы сразу получить id
                                $IOtowerSave->tower()->save($towerSave);

                                // сохранение имени по-новому в Names
                                $towerSave->setName($getAclineID, $point['viewName']);

                                $currentPoint['DbID'] = $towerSave->id;
                                $currentPoint['dbIOID'] = $IOtowerSave->id;
                            }

                            if ($point['type'] == 'customer') {
                                // потребитель

                                // проверить, передали ли dbID
                                if ($point['dbID'] != null) {
                                    // dbID не пустое
                                    // проверить, есть ли уже в базе
                                    $customerSave = Customer::find($point['dbID']);
                                    if ($customerSave) {
                                        // в базе есть
                                        $IOcustomerSave = Identifiedobject::where('id', $customerSave['identifiedobject_id'])->get()->first();
                                        if (!$IOcustomerSave) {
                                            $IOcustomerSave = new Identifiedobject;
                                        }
                                    } else {
                                        // в базе еще нет
                                        $customerSave = new Customer;
                                        $IOcustomerSave = new Identifiedobject;
                                    }
                                } else {
                                    // dbID пустое
                                    $customerSave = new Customer;
                                    $IOcustomerSave = new Identifiedobject;
                                }

                                $customerSave->photos = (isset($point['photos']) and !is_null($point['photos'])) ? json_encode($point['photos']) : '';

                                // сохранить в IO
                                $IOcustomerSave->name = $point['name'];
                                $IOcustomerSave->localname = $point['localName']; // localName
                                $IOcustomerSave->address = $point['address'];
                                $IOcustomerSave->lat = $point['lat'];
                                $IOcustomerSave->long = $point['long'];
                                $IOcustomerSave->voltage_id = $getAclineBaseVoltage;
                                $IOcustomerSave->save();
                                // сохранить через родителя IO, чтобы сразу получить id
                                $IOcustomerSave->customer()->save($customerSave);

                                $currentPoint['DbID'] = $customerSave->id;
                                $currentPoint['dbIOID'] = $IOcustomerSave->id;
                            }

                            // проверка на наличие разрядника, ОПН
                            for ($dischargerTypeN = 1; $dischargerTypeN <= 2; $dischargerTypeN++) {
                                switch ($dischargerTypeN) {
                                    case 1:
                                        $dischargerField = 'eqDischarger';
                                        break;
                                    case 2:
                                        $dischargerField = 'eqOPN';
                                        break;
                                }

                                if ($point[$dischargerField] == 1) {
                                    $dischargerSave = Discharger::where('type', $dischargerTypeN)->where('startIO_id', $currentPoint['dbIOID'])->get()->first(); // только на случай, если только один разьединитель, не несколько!
                                    if ($dischargerSave) {
                                        // в базе есть
                                        $IOdischargerSave = Identifiedobject::where('id', $dischargerSave['identifiedobject_id'])->get()->first();
                                        if (!$IOdischargerSave) {
                                            $IOdischargerSave = new Identifiedobject;
                                        }
                                    } else {
                                        // в базе еще нет
                                        $dischargerSave = new Discharger();
                                        $IOdischargerSave = new Identifiedobject;
                                    }

                                    $dischargerSave->type = $dischargerTypeN;
                                    $dischargerSave->startIO_id = $currentPoint['dbIOID'];
                                    $dischargerSave->dischargerinfo_id = $point[($dischargerField . 'Info')];

                                    // сохранить в IO
                                    $IOdischargerSave->save();

                                    // сохранить через родителя IO, чтобы сразу получить id
                                    $IOdischargerSave->discharger()->save($dischargerSave);
                                }
                            }

                            // массив, в котором буду для себя сохранять id точки с карты и номер присвоенный в БД при сохранении
                            $myArrSaveIDs[] = [
                                'mapID' => $point['mapID'],
                                'dbID' => $currentPoint['DbID'],
                                'dbIOID' => $currentPoint['dbIOID'],
                            ];
                        }

                        // --------------------------------------------------------------
                        // проверка на наличие разьединителя, реклоузера, выключателя нагрузки
                        for ($disconnectorTypeN = 1; $disconnectorTypeN <= 3; $disconnectorTypeN++) {

                            switch ($disconnectorTypeN) {
                                case 1:
                                    $disconnectorField = 'eqDisconnector';
                                    break;
                                case 2:
                                    $disconnectorField = 'eqReklouzer';
                                    break;
                                case 3:
                                    $disconnectorField = 'eqVNa';
                                    break;
                            }

                            if ($getObjects[$segmentSpan][($disconnectorField . $pointName)] == 1) {
                                $disconnectorSave = Disconnector::where('type', $disconnectorTypeN)->where('startIO_id', $currentPoint['dbIOID'])->where('span_id', $spanSave->id)->get()->first();
                                if ($disconnectorSave) {
                                    // в базе есть
                                    $IOdisconnectorSave = Identifiedobject::where('id', $disconnectorSave['identifiedobject_id'])->get()->first();
                                    if (!$IOdisconnectorSave) {
                                        $IOdisconnectorSave = new Identifiedobject;
                                    }
                                } else {
                                    // в базе еще нет
                                    $disconnectorSave = new Disconnector;
                                    $IOdisconnectorSave = new Identifiedobject;
                                }

                                $disconnectorSave->type = $disconnectorTypeN;
                                $disconnectorSave->startIO_id = $currentPoint['dbIOID'];
                                $disconnectorSave->span_id = $spanSave->id;
                                $disconnectorSave->disconnectorinfo_id = $getObjects[$segmentSpan][($disconnectorField . $pointName . 'Info')];

                                // сохранить в IO
                                $IOdisconnectorSave->save();

                                // сохранить через родителя IO, чтобы сразу получить id
                                $IOdisconnectorSave->disconnector()->save($disconnectorSave);
                            }
                        }
                        // --------------------------------------------------------------

                        // после того, как id вершин известны - записать снова в пролеты/участки
                        switch ($pointN) {
                            case 1:
                                $spanSave->startIO_id = $currentPoint['dbIOID'];
                                break;
                            case 2:
                                $spanSave->endIO_id = $currentPoint['dbIOID'];
                                break;
                        }
                        $spanSave->save();
                    }
                }
            }
        }

        // записать в черновик присвоенные IDs (как и на фронте, чтоб одинаково было - потому что в черновике бекенда еще null может быть, если только одно сохранение было)
        foreach ($myArrSaveIDs as $item) {
            $getObjects[$item['mapID']]['dbID'] = $item['dbID'];
            $getObjects[$item['mapID']]['dbIOID'] = $item['dbIOID'];
        }
        // заново сохранить черновик
        $aclineSave->map_json = json_encode($getObjects);
        $aclineSave->save();

        // возвращаемый параметр
        return [
            'message' => "Успешно сохранено!",
            'aclineID' => $getAclineID,
            'aclineName' => $getAclineName,
            'ids' => $myArrSaveIDs,
        ];
    }

    // ------------------------------------------------------------------
    // загрузка других точек для карты, которые не входят в указанную ЛЭП
    public function mapLoadNearObjects(Request $request)
    {
        // переданные параметры через запрос post
        $getAclineID = $request['aclineID']; // ID линии
        $getBounds = $request['bounds']; // координаты видимой части карты

        if ($getBounds) {
            // координаты передали

            $thisLineIDs = null;
            if ($getAclineID) {
                // ID линии передали - это не новая
                // "худая" загрузка всех строк всех обьектов без связей для линии ("матрешка" вложенности, только ID-шки) (ч.1)
                $thisLineIDs = self::getThisLineIDs($getAclineID);
            }

            // получить список ближайших обьектов по дистанции
            $getArrOnBounds = $this->yandexMapService->getNearObjects($getBounds, $thisLineIDs);

            return $getArrOnBounds;
        }
    }

    // ------------------------------------------------------------------
    // импорт данных их файла для карты
    public function mapImport(Request $request)
    {
        // полученные переменные через AJAX
        $file = $_FILES['file'];

        // загрузить файл и перенести в папку
        $fileFullName = public_path() . '/' . 'uploads/models/map/kml/' . $file['name'];
        if (!move_uploaded_file($file['tmp_name'], $fileFullName)) {
            return 'Ошибка!';
        }

        // расширение файла
        $ext = strtoupper(pathinfo($fileFullName, PATHINFO_EXTENSION));
        // прочитать содержимое
        $content = '';
        if ($ext == 'KML' or $ext == 'GPX' or $ext == 'XSDE' or $ext == 'XML') {
            // читать как XML
            $content = simplexml_load_file($fileFullName);
        } else if ($ext == 'TRACK') {
            $content = file_get_contents($fileFullName);
        }
        //print_r($content);

        // возвращаемый параметр
        return json_encode($content);
    }

    // ------------------------------------------------------------------
    // сохранение карты как SVG
    // !!! не используется
    public function mapSaveAsSVG()
    {
        // полученные переменные через AJAX
        $mySVGMap = $_POST['SVGMap'];

        $fileSave = public_path() . '/uploads/aclineMap.svg';
        $file = fopen($fileSave, "w+"); // открываем файл
        fwrite($file, $mySVGMap); // пишем в него
        fclose($file); // закрываем
        return response()->json('OK', 200);
    }

    // ------------------------------------------------------------------
    // "по-новому" с SVG

    // ------------------------------------------------------------------
    // загрузка карты (нужен только черновик)
    public function vueMapSvgLoad(Request $request)
    {
        // переданные параметры через запрос post
        $getAclineID = $request['aclineID'];

        $acline = Acline::where('id', $getAclineID)->get()->first();

        // возвращаемый парамметр
        return $acline;
    }
}
