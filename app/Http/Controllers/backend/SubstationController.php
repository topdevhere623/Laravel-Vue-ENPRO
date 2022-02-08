<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Breaker;
use App\Models\Circuitbreaker;
use App\Models\Disconnector;
use App\Models\Disconnectorfuse;
use App\Models\Fuse;
use App\Models\Loadbreakswitch;
use App\Models\Powertransformer;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Exception;
use Illuminate\Http\Client\Response;
use Log;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Substation;
use App\Models\Substationinfo;
use App\Models\Substationfunction;
use App\Models\Address;
use App\Models\Asset;
use App\Models\Identifiedobject;
use App\Models\Connectivitycode;
use App\Models\Acline;
use App\Models\Aclinesegment;
use App\Models\Busbarsection;
use App\Models\Classname;
use App\Models\File;
use App\Models\Span;
use App\Models\Terminal;
use App\Models\Tower;

// контроллер модели
class SubstationController extends Controller
{
    protected $origins = [];

    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // ------------------------------------------------------------------
    // вывод списка
    public function index()
    {
        // содержимое загрузить позже Vue

        // открыть вюшку
        return view('backend.substation.index');
    }

    // ------------------------------------------------------------------
    // список (Vue)
    public function vueIndex(Request $request)
    {
        // переданные параметры через запрос post
        $getPage = $request['page']; // для пагинации
        $getFilterName = $request['filterName']; // поисковое выражение
        $getSortCol = $request['sortCol']; // сортировка
        $getSortDirect = $request['sortDirect']; // сортировка

        // для пагинации - кол-во записей на странице
        $rowsPerPage = $this->commonService->getAdmminSetting('setting_paginate_admin');

        $myReturn = Substation::with('identifiedobject', 'busbarsections')
            ->selectRaw('substation.id, substation.updated_at, identifiedobject.name, identifiedobject.address, CONCAT(identifiedobject.lat, ", ", identifiedobject.long) as coord')
            ->leftJoin('identifiedobject', 'substation.identifiedobject_id', '=', 'identifiedobject.id')
            ->when(isset($getFilterName), function ($query) use ($getFilterName) {
                return $query->where('identifiedobject.name', 'like', '%' . $getFilterName . '%');
            })
            ->when(isset($getSortCol), function ($query) use ($getSortCol, $getSortDirect) {
                return $query->orderBy($getSortCol, $getSortDirect);
            })
            ->paginate($rowsPerPage);

        // возвращаемый параметр
        return $myReturn;
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
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Substation', $id);
        // редирект
        return redirect()->back();
    }

    // ------------------------------------------------------------------
    // вывод одной строки
    public function edit(Request $request, $id = null)
    {
        // проверить параметры, возможно переданные через post запрос
        $thisModal = $request->input('thisModal');

        // контент
        if ($id) {
            $content = Substation::findOrFail($id);
        } else {
            $content = new Substation;
        }

        // справочники и другие дополнительные сведения
        $substationinfos = Substationinfo::all();
        $substationfunctions = Substationfunction::all();
        $addresses = Address::all();
        $assets = Asset::all();
        $identifiedobjects = Identifiedobject::all();

        //\DB::enableQueryLog();

        $busbarsections = Substation::with('busbarsections.identifiedobject')->find($id);

        //dd(\DB::getQueryLog());
        //Log::info($busbarsections);

        // возвращаемый параметр
        if (is_null($thisModal)) {
            // открыть вьюшку
            return view('backend.substation.edit', compact('content', 'substationinfos', 'substationfunctions', 'addresses', 'assets', 'busbarsections', 'identifiedobjects'));
        } else {
            // вернуть готовый рендер страницы
            $html = view('backend.substation.editcontent')->with(
                [
                    'thisModal' => $thisModal,
                    'content' => $content,
                    'substationinfos' => $substationinfos,
                    'substationfunctions' => $substationfunctions,
                    'busbarsections' => $busbarsections,
                    'addresses' => $addresses,
                    'identifiedobjects' => $identifiedobjects,
                    'assets' => $assets
                ]
            )->render();
            return response()->json(['html' => $html]);
        }
    }

    public function schemeTemplate($id = null, Request $request)
    {
        $templateFileName = 'uploads/default/substation_template.json';
        if($request->method() == 'GET') {
            $template = [];
            if(is_file($templateFileName)) $template = json_decode(file_get_contents($templateFileName));
            //if(!$template) $template = [];
            return response()->json($template);
        } else if($request->method() == 'PUT' || $request->method() == 'POST') {
            if(file_put_contents($templateFileName, json_encode($request->json()->all())) === false) {
                return response()->json(['error' => 'Template not saved'],
                    \Illuminate\Http\Response::HTTP_BAD_REQUEST);
            } else {
                $template = json_decode(file_get_contents($templateFileName));
                return response()->json($template);
            }
        }
    }

    public function scheme($id = null, Request $request)
    {
        /** @var Substation $substation */
        $substation = Substation::find($id);
        if(!$substation) {
            return response()->json(['error' => 'There is not have substation with id '. $id],
                \Illuminate\Http\Response::HTTP_BAD_REQUEST);
        }
        if($request->method() == 'GET') {
            return response()->json($substation->getScheme());
        } else if ($request->method() == 'POST' or $request->method() == 'PUT')
        {
            $substation->setScheme($request->json()->all());
            try {
                $substation->save();
                return response()->json($substation->getScheme());
            } catch (\Exception $e) {
                return response()->json(['error' => 'There is error while substation saving'],
                    \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        $a = $id;
    }

    // ------------------------------------------------------------------
    // сохранение данных
    public function update($id = null, Request $request)
    {
        // проверить параметр модального окна
        $thisModal = $request->input('thisModal');
        /** @var Substation $model */

        if ($id) $model = Substation::findOrFail($id);
        else $model = new Substation();
        if ($request->json()->count() && $request->json()->all()['data'] ||  @$request->json()->all()['equipment']) {
            foreach ($request->json()->all()['data'] as $baseVoltage => $busBarSection) {
                if ($baseVoltage == '6кВ') $voltage = 6;
                else if ($baseVoltage == '10кВ') $voltage = 10;
                else $voltage = 380;
                foreach ($busBarSection as $data) {
                    $busBur = @$data['busbursection'];
                    $needAddNew = 1;
                    $busBarSection = null;
                    if (@$busBur['id']) {
                        $needAddNew = 0;
                        $busBarSection = Busbarsection::find($busBur['id']);
                    }
                    if (!$busBarSection) {
                        foreach ($model->busbarsections()->get() as $currentBb) {
                            /** @var Busbarsection $currentBb */
                            /** @var Identifiedobject $io */
                            $io = $currentBb->identifiedobject()->get()->get(0);
                            if (($io->name == @$busBur['name'] || $io->keylink == @$busBur['keylink']) && $io->voltage_id == $voltage && $io->classname()->get()->get(0)->classname == @$busBur['type']) {
                                $needAddNew = 0;
                                $busBarSection = $currentBb;
                                break;
                            }
                        }
                    }
                    if ($needAddNew) {
                        $busBarSection = new Busbarsection();
                        $identifiedObject = new Identifiedobject();
                        $asset = new Asset();
                        $asset->save();
                        $identifiedObject->voltage_id = $voltage;
                        $identifiedObject->name = @$busBur['name'];
                        $className = Classname::where('classname', @$busBur['type'])->get()->get(0);
                        if (!$className && @$busBur['type']) {
                            $className = new Classname();
                            $className->classname = @$busBur['type'];
                            $className->save();
                        }
                        $identifiedObject->classname_id = $className->id;
                        $identifiedObject->save();
                        $busBarSection->asset_id = $asset->id;
                        $busBarSection->identifiedobject_id = $identifiedObject->id;
                        $busBarSection->bbsecinsulatorinfo_id = 0;
                        $busBarSection->bbsmaterial_id = 0;
                        $model->busbarsections()->save($busBarSection);
                    }
                    $terminals = @$data['terminals'];
                    foreach ($terminals as $terminal) {
                        $addTerminal = 1;
                        $theTerminal = null;
                        if (@$terminal['id']) {
                            $theTerminal = Terminal::find($terminal['id']);
                            $addTerminal = 0;
                        }
                        if (!$theTerminal) {
                            foreach ($busBarSection->terminal()->get() as $busTerminal) {
                                /** @var Terminal $busTerminal */
                                if ($busTerminal->identifiedobject()->get()->get(0)->name == $terminal['name']) {
                                    $addTerminal = 0;
                                    $theTerminal = $busTerminal;
                                    break;
                                }
                            }
                        }
                        if ($addTerminal) {
                            $theTerminal = new Terminal();
                            $identifiedObject = new Identifiedobject();
                            $identifiedObject->voltage_id = $voltage;
                            $identifiedObject->name = $terminal['name'];
                            $identifiedObject->enobj_id = $model->identifiedobject()->get()->get(0)->id;
                            $identifiedObject->localname = @$terminal['busBarConnectionDotNumber'];
                            $asset = new Asset();
                            $asset->save();
                            $identifiedObject->asset_id = $asset->id;
                            $identifiedObject->save();
                            $theTerminal->identifiedobject_id = $identifiedObject->id;
                            $connectivityNode = new Connectivitycode();
                            $connectivityNode->save();
                            $theTerminal->connectivitycode_id = $connectivityNode->id;
                            $busBarSection->terminal()->save($theTerminal);
                        }
                    }
                    $busBarSection->save();
                }
            }
            $model->save();
            foreach ($request->json()->all()['equipment'] as $className => $equipments) {
                /** @var Classname $cn */
                $cn = Classname::whereClassname($className)->get()->get(0);
                if(!$cn) {
                    $cn = new Classname();
                    $cn->classname = $className;
                    $cn->save();
                }
                foreach ($equipments as $equipment) {
                    $eqIo = $equipment['IdentifiedObject'];
                    /** @var Identifiedobject $equipmentIo */
                    $equipmentIo = Identifiedobject::whereKeylink($eqIo['keyLink'])->
                    where('enobj_id', $model->identifiedobject_id)
                        ->where('classname_id', $cn ? $cn->id : 0)
                        ->get()->get(0);
                    if(!$equipmentIo) {
                        $equipmentIo = new Identifiedobject();
                        $equipmentIo->asset_id = 0;
                    }
                    $voltage_id = 380;
                    $voltage = @$eqIo['voltage'];
                    if($voltage == '6кВ') $voltage = 6;
                    if($voltage == '10кВ') $voltage = 10;
                    if($voltage == '0,4кВ') $voltage = 380;
                    if($voltage == '35кВ') $voltage = 35;
                    if($voltage == '110кВ') $voltage = 110;
                    $equipmentIo->setName($eqIo['name']);
                    $equipmentIo->keylink = $eqIo['keyLink'];
                    $equipmentIo->classname_id = $cn ? $cn->id : 0;
                    $equipmentIo->setDescription($eqIo['description']);
                    $equipmentIo->enobj_id = $model->identifiedobject_id;
                    if($className == 'PowerTransformer') {
                        if($equipmentIo->asset_id) $asset = Asset::find($equipmentIo->asset_id);
                        else $asset = new Asset();
                        $asset->assetinfokey = $equipment['Asset']['assetinfokey'];
                        $asset->save();
                        $equipmentIo->asset_id = $asset->id;
                        $equipmentIo->save();
                    } else {
                        $equipmentIo->voltage_id = $voltage;
                        $equipmentIo->save();
                    }
                    switch ($className) {
                        case 'Disconnector':
                            $ob = Disconnector::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Disconnector();
                                $ob->asset_id = 0;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id'])) $ob->save();
                            break;
                        case 'Breaker':
                            $ob = Breaker::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Breaker();
                                $obAsset = new Asset();
                                $obAsset->save();
                                $equipmentIo->asset_id = $obAsset->id;
                                $ob->asset_id = $obAsset->id;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id']))$ob->save();
                            break;
                        case 'Fuse':
                            $ob = Fuse::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Fuse();
                                $ob->asset_id = 0;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id']))$ob->save();
                            break;
                        case 'LoadBreakSwitch':
                            $ob = Loadbreakswitch::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Loadbreakswitch();
                                $ob->asset_id = 0;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id']))$ob->save();
                            break;
                        case 'DisconnectorFuse':
                            $ob = Disconnectorfuse::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Disconnectorfuse();
                                $ob->asset_id = 0;
                                $ob->drive_id = 0;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id']))$ob->save();
                            break;
                        case 'CircuitBreaker':
                            $ob = Circuitbreaker::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Circuitbreaker();
                                $obAsset = new Asset();
                                $obAsset->save();
                                $equipmentIo->asset_id = $obAsset->id;
                                $ob->asset_id = $obAsset->id;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            if($ob->isDirty(['identifiedobject_id'])) $ob->save();
                            break;
                        case 'PowerTransformer':
                            $ob = Powertransformer::where('identifiedobject_id', $equipmentIo->id)->get()->get(0);
                            if(!$ob) {
                                $ob = new Powertransformer();
                                $ob->tapchangecontrol_id = 0;
                                $ob->tankdesign_id = 0;
                                $ob->thetmometeroil_id = 0;
                            }
                            $ob->identifiedobject_id = $equipmentIo->id;
                            $ob->asset_id = $asset->id ? $asset->id : 0;
                            if($ob->isDirty()) $ob->save();
                            break;
                    }
                }


            }
            return response()->json(['id' => $id]);
        }

        if ($request['img'] instanceof UploadedFile) {
            $this->commonCrudService->store('Substation', $id, $request, 'img');
        }
        if ($request['scheme'] instanceof UploadedFile) {
            $this->commonCrudService->store('Substation', $id, $request, 'scheme', 'file');
        }

        if ($request["_busbarsection_name_"]) {
            foreach ($request["_busbarsection_name_"] as $key => $value) {
                $io = Identifiedobject::findOrFail($key);
                $io->name = $value;
                $io->keylink = $request["_busbarsection_keylink_"][$key];
                $io->save();
            }
        }
        /** @var Identifiedobject $modelIo */
        $modelIo = $model->identifiedobject()->get()->get(0);
        if (!$modelIo) $modelIo = new Identifiedobject();
        if ($request['_io_name']) $modelIo->name = $request['_io_name'];
        if ($request['_io_localname']) $modelIo->localname = $request['_io_localname'];
        if ($request['_io_lat']) $modelIo->lat = $request['_io_lat'];
        if ($request['_io_long']) $modelIo->long = $request['_io_long'];
        if ($request['_io_address']) $modelIo->address = $request['_io_address'];

        $modelIo->save();
        $model->identifiedobject_id = $modelIo->id;

        $model->substationinfo_id = $request->substationinfo_id;
        $model->passport = $request->passport;
        $model->save();

        if (is_null($thisModal)) {
            // редирект
            return redirect(route('substation.index'));
        } else {
            // далее закрытие модального окна
            $id = $model->id;
            $myName = $model->identifiedobject->name;
            return response()->json(['id' => $id, 'name' => $myName]);
        }
    }

    // ------------------------------------------------------------------
    public function show(Request $request, $id = null)
    {
        /** @var Substation $content */
        $content = Substation::findOrFail($id);
        /** @var Identifiedobject $identifiedobjects */
        $identifiedobjects = $content->identifiedobject()->get()->get(0);
        $image = $content->imagePath('img');
        if ($image) {
            $svgData = file_get_contents($image);
            if (strpos($svgData, 'iso-8859-1')) {
                $svgData = mb_convert_encoding($svgData, 'utf-8', 'cp1251');
                $svgData = str_replace('iso-8859-1', 'utf-8', $svgData);
                $svgData = str_replace('Arial Unicode MS', 'IBMPlexMono', $svgData);
                $svgData = str_replace('Lucida Sans Unicode', 'IBMPlexMono', $svgData);
                $svgData = str_replace('#339966', '#465FB9', $svgData);
                $svgData = str_replace(
                    'font-size: 20pt; fill: #000000',
                    'font-size: 20pt; fill: #465FB9;"',
                    $svgData
                );
                $svgData = str_replace('#FF00FF', '#ff0000', $svgData);
                $svgData = str_replace(
                    'fill="#FFFFFF"  style="stroke:#FFFFFF; stroke-width:1;"',
                    'fill="#202225"  style="stroke:#202225; stroke-width:1;"',
                    $svgData
                );
                $svgData = str_replace('#555555', '#ffffff', $svgData);
                $svgData = str_replace(
                    'fill="#FFFFFF"  style="stroke:#808080; stroke-width:1;"',
                    'fill="#202225"  style="stroke:#202225; stroke-width:1;"',
                    $svgData
                );
                $svgData = str_replace('#333333', '#ffffff', $svgData);
                $svgData = str_replace('#000000', '#ffffff', $svgData);
                $svgData = str_replace(
                    'fill="#FFFFFF"  style="stroke:#ffffff;',
                    'fill="#202225"  style="stroke:#202225;',
                    $svgData
                );

                $pattern = '/svg\\s*width=\\"([\\d.]*)"\\s*height="([\\d.]*)\\"/';
                $svgData = preg_replace($pattern, 'svg width="3000" height="1300" viewpoint="0 0 2500 1000"', $svgData);
                $pattern = '/<text[^>]*[^<]*[^>]*/';
                $svgData = preg_replace($pattern, '', $svgData, 1);
            }
        } else $svgData = '';

        return $html = view('backend.substation.show')->with(
            [
                'content' => $content,
                'svg' => $svgData,
                'identifiedobject' => $identifiedobjects
            ]
        );
    }

    // ------------------------------------------------------------------
    public function icon($id = null)
    {
        /** @var Substation $content */
        $content = Substation::findOrFail($id);
        /** @var Identifiedobject $identifiedobjects */
        $identifiedobjects = $content->identifiedobject()->get()->get(0);
        $iconFolder = 'assets/backend/icons/mainmap/';
        $addFolder = 'blue/';
        if (trim($content->passport) == 'М') $addFolder = 'turquoise/';
        if (trim($content->passport) == 'Л') $addFolder = 'green/';
        if (trim($content->passport) == 'С') $addFolder = 'purple/';
        $svgFilename = $addFolder . 'tp.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 3, 'UTF-8') == ' КТ' || mb_substr(trim($identifiedobjects->name), 0, 3, 'UTF-8') == 'КТП') $svgFilename = $addFolder . 'ktpn.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 2, 'UTF-8') == 'РП') $svgFilename = $addFolder . 'rp.svg';
        if (mb_substr(trim($identifiedobjects->name), 0, 2, 'UTF-8') == 'ПС') {
            $svgFilename = 'ps-r.svg';
            if (trim($content->passport) == 'М') $svgFilename = 'ps-m.svg';
            if (trim($content->passport) == 'Л') $svgFilename = 'ps-l.svg';
            if (trim($content->passport) == 'С') $svgFilename = 'ps-s.svg';
        }
        $svg = file_get_contents($iconFolder . $svgFilename);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    // ------------------------------------------------------------------
    public function clear($id = null, Request $request)
    {
        /** @var Substation $model */

        $model = Substation::findOrFail($id);
        $terminals = $model->getTerminals(0);
        $bussy = [];
        foreach ($terminals as $terminal) {
            /** @var Terminal $terminal */
            $connected = $terminal->getConnectedTerminals();
            if ($connected) {
                $bussy[] = ['terminal' => $terminal->identifiedobject()->get()->get(0), 'connected' => $connected];
            }
        }
        if ($bussy) {
            $error = ['error' => 'bussy', 'bussy' => $bussy];
            //return response()->json(['id' => $id, 'error' => $error], 403);
        }
        foreach ($terminals as $terminal) {
            $terminal->delete();
        }
        foreach ($model->busbarsections()->get() as $busbarsection) {
            $busbarsection->delete();
        }
        return response()->json(['id' => $id, 'success' => 'success']);
    }

    public function getAndSaveEquipmnets(DOMXPath $xpath, Substation $substation)
    {
        $returnObjects = [];
        $needToFind = [
            'Disconnector',
            'Breaker',
            'PowerTransformer',
            'Fuse',
            'LoadBreakSwitch',
            'DisconnectorFuse',
            'CircuitBreaker',
            'ProtectedSwitch',
            'ShortCircuitingSwitch'
        ];

        foreach ($needToFind as $item) {
            $found = $xpath->query('//TechData[@className="' . $item . '"]');
            /** @var DOMNode $itemNode */
            foreach ($found as $itemNode) {
                $dispName = $this->clearModusString($itemNode->getAttribute('DispName'));
                $keyLink = $this->clearModusString($itemNode->getAttribute('keyLink'));
                $enObj = $substation->identifiedobject_id;
                $voltage = $this->clearModusString($itemNode->getAttribute('voltage'));
                $mark = $this->clearModusString($itemNode->getAttribute('mark'));
                //if($voltage == '6кВ') $voltage = 6;
                //if($voltage == '10кВ') $voltage = 10;
                //if($voltage == '0,4кВ') $voltage = 380;
                if($item == 'PowerTransformer') {
                    $voltageArray = [];
                    $widings = $xpath->query('TechData[@className="TransformerWinding"]', $itemNode);
                    foreach ($widings as $widing) {
                        $voltageArray[] = $this->clearModusString($widing->getAttribute('voltage'));
                    }
                }
                $wiklType = $this->clearModusString($itemNode->getAttribute('wiklType'));
                $ioObjects = null;
                //$ioObjects = Identifiedobject::whereKeylink($keyLink)->where('enobj_id', $enObj)->getModels();
                if(!$ioObjects) {
                    if(!@$returnObjects[$item]) @$returnObjects[$item] = [];
                    $addObject = null;
                    switch ($item) {
                        case 'Disconnector':
                        case 'Breaker':
                        case 'Fuse':
                        case 'LoadBreakSwitch':
                        case 'DisconnectorFuse':
                        case 'CircuitBreaker':
                            $addObject = ['IdentifiedObject' =>
                                [
                                    'id' => 0,
                                    'className' => $item,
                                    'name' => $dispName,
                                    'keyLink' => $keyLink,
                                    'enobj_id' => $enObj,
                                    'voltage' => $voltage,
                                    'description' => $wiklType
                                ]
                            ];
                            break;
                        case 'PowerTransformer':
                            $addObject = ['IdentifiedObject' =>
                                [
                                    'id' => 0,
                                    'className' => $item,
                                    'name' => $dispName,
                                    'keyLink' => $keyLink,
                                    'enobj_id' => $enObj,
                                    'description' => implode('/', $voltageArray)
                                ],
                                'Asset' => [
                                    'assetinfokey' => $mark
                                ]

                            ];
                            break;
                    }
                    $returnObjects[$item][] = $addObject;
                }
            }

        }
        return $returnObjects;
    }

    protected function getTransformersFromXSDEScheme(DOMXPath $xpath = null, Substation $substation = null)
    {
        $transformersNodes = $xpath->query('//TechData[@className="PowerTransformer"]');
        $transformers = [];
        foreach($transformersNodes as $transformersNode) {
            $keyLink = $transformersNode->getAttribute('keyLink');
            /** @var Identifiedobject $io */
            $io = Identifiedobject::where('keylink', $keyLink)->
                where('enobj_id', $substation->identifiedobj_id)->get()->get(0);
            /** @var Powertransformer $transformer */
            $transformer = null;
            $isnew = 0;
           if(!$io) {
               $io = new Identifiedobject();
               $asset = new Asset();
           } else {
               $transformer = Powertransformer::whereIdentifiedobject_id($io->id);
               /** @var Asset $asset */
               $asset = $io->asset()->get()->get(0);
               if($asset) $asset = New Asset();
           }
           if(!$transformer) {
               $transformer = new Powertransformer();
               $isnew = 1;
           }
           $io->className = 'PowerTransformer';
           $io->name = $this->clearModusString($transformersNode->getAttribute('dispName'));
           $io->localname = $this->clearModusString($transformersNode->getAttribute('fDispNum'));
           $asset->assetinfokey = $this->clearModusString($transformersNode->getAttribute('mark'));
           $io->asset()->newRelatedInstanceFor($asset);
            //$asset =
        }
    }

    protected function clearModusString($string = '')
    {
        $string = str_replace('RUS', '', $string);
        $string = str_replace('&npsp;', '', $string);
        $string = trim($string);
        return $string;
    }

    // ------------------------------------------------------------------
    public function parseScheme($id, Request $request)
    {
        libxml_disable_entity_loader(false);
        /** @var Substation $substation */
        $substation = Substation::findOrFail($id);
        $dom = new DOMDocument(1.0, 'utf-8');
        if (!$request->file && !$substation->getXsde()) {
            throw new Exception('Wrong file');
        } else if($request->file) {
            $xml = $dom->loadXML(file_get_contents($request->file->path()));
        } else {
            $xml = $dom->loadXML($substation->getXsde());
        }
        $data = '';
        $terminals = [];
        if ($xml) {
            $xpath = new DOMXPath($dom);
            $equipments = $this->getAndSaveEquipmnets($xpath, $substation);

            //$this->getTransformersFromXSDEScheme($xpath, $substation);
            $techDataVoltageLevelObjects = $xpath->query('//TechsRegistry/TechData[@className="VoltageLevel"]');

            $tpVoltages = [];
            foreach ($techDataVoltageLevelObjects as $techDataObject) {
                $aclineSegments = $xpath->query('TechData[@className="ACLineSegment"]', $techDataObject);
                $voltage = $techDataObject->getAttribute('voltage');
                if ($aclineSegments->count()) {
                    if (!@$tpVoltages[$voltage]) $tpVoltages[$voltage] = [];
                }
                foreach ($aclineSegments as $aclineSegment) {
                    /** @var DOMNode $aclineSegment */
                    $dispName = $aclineSegment->getAttribute('DispName');
                    $objects = $this->getObjectsToBusbarSectionByAclineSegment($xpath, $aclineSegment);
                    if (!$objects) continue;
                    $terminal = [];
                    $terminal['name'] = str_replace('RUS', '', $dispName);
                    $terminal['name'] = str_replace('&nbsp;', '', $terminal['name']);
                    $keylink = $aclineSegment->getAttribute('keyLink');
                    if(!$keylink) $keylink = $terminal['name'];
                    $terminal['keylink'] = $keylink;

                    $terminal['voltageLevel'] = $aclineSegment->getAttribute('voltage');
                    $busBarSection = array_pop($objects);
                    $beforeBusBarSectionObject = array_pop($objects);
                    if ($beforeBusBarSectionObject) {
                        $terminal['busBarConnectionDotNumber'] = $this->getConductingEquipmentForTerminal($beforeBusBarSectionObject, $xpath);
                        $objects = $objects ? $objects : [];
                        $objects[] = $beforeBusBarSectionObject;
                        $terminal['objects'] = [];
                        $beforeConnection =  $terminal['keylink'];
                        $afterConnection = '';
                        for ($i = 0; $i < count($objects); $i++) {
                            /** @var DOMNode $object */
                            if(!$beforeConnection) $beforeConnection = '';
                            $object = $objects[$i];
                            if($i == 0) {
                                $beforeConnection = $terminal['keylink'];
                            }
                            $afterConnection = '';
                            if($i == count($objects) - 1) {
                                $afterConnection = $busBarSection->getAttribute('keyLink');
                            }
                            $objectName = $object->getAttribute('DispName');
                            $objectName = str_replace('RUS', '', $objectName);
                            $objectName = str_replace('&nbsp;', '', $objectName);
                            $pSRType = $object->getAttribute('pSRType');
                            $wiklType = $object->getAttribute('wiklType');
                            $normalOpen = $object->getAttribute('normalOpen');
                            $terminal['objects'][$i] = [
                              'className' => $object->getAttribute('className'),
                              'keylink' =>   $object->getAttribute('keyLink') ? $object->getAttribute('keyLink') : $keylink,
                              'connection1' => $beforeConnection,
                              'connection2' => $afterConnection,
                              'name' => $objectName,
                              'psrtype' => $pSRType,
                              'wikltype' => $wiklType,
                              'normalopen' => $normalOpen
                            ];
                            if($i > 0 && !$terminal['objects'][$i - 1]['connection2']) {
                                $terminal['objects'][$i - 1]['connection2'] = $terminal['objects'][$i]['keylink'];
                            }
                            $beforeConnection  = $terminal['objects'][$i]['keylink'];
                        }
                    }
                    $rtid = $busBarSection->getAttribute('RTID');
                    if (!@$tpVoltages[$voltage][$rtid]) {
                        $tpVoltages[$voltage][$rtid] = ['busbursection' => [], 'terminals' => []];
                    }
                    if ($busBarSection->getAttribute('className') == 'TransformerWinding') {
                        $busBarSection = [
                            'type' => 'TransformerWinding',
                            'name' => str_replace('RUS', '', $busBarSection->getAttribute('keyLink')),
                            'keylink' => str_replace('RUS', '', $busBarSection->getAttribute('keyLink')),
                            ];
                    } else if ($busBarSection->getAttribute('className') == 'BusBarSection') {
                        $bbName = str_replace('RUS', '', $busBarSection->getAttribute('DispName'));
                        $bbName = str_replace('&nbsp;', '', $bbName);
                        $busBarSection = ['type' => 'BusBarSection', 'name' => $bbName,
                            'keylink' => str_replace('RUS', '', $busBarSection->getAttribute('keyLink'))];
                    } else {
                        $busBarSection = ['type' => 'notResolved', 'name' => 'Ошибка в схеме, невозможно найти Терминал'];
                    }
                    $tpVoltages[$voltage][$rtid]['busbursection'] = $busBarSection;
                    $tpVoltages[$voltage][$rtid]['terminals'][] = $terminal;
                }
            }
            $r = [];
            foreach ($tpVoltages as $key => $baseVoltage) {
                usort($baseVoltage, function ($a, $b) {
                    return strcasecmp($a['busbursection']['name'], $b['busbursection']['name']);
                });
                $r[$key] = $baseVoltage;
            }
            $data = $r;
            krsort($data);
        } else {
            throw new Exception('Wrong file');
        }
        $substation->setXsde($dom->saveXML());
        $substation->save();
        return response()->json(['data' => $data, 'substation' => $substation, 'equipment' => $equipments]);
        return view('backend.substation.parseData', ['content' => $substation, 'substation' => $substation, 'data' => $data]);
    }

    // ------------------------------------------------------------------
    public function parse($id)
    {
        /** @var Substation $substation */
        $substation = Substation::findOrFail($id);
        $data = [];
        if ($substation->busbarsections()->get()->count()) {
            foreach ($substation->busbarsections()->get() as $busBarSection) {
                /**@var Busbarsection $busBarSection */
                /** @var Identifiedobject $bbio */
                $bbio = $busBarSection->identifiedobject()->get()->get(0);
                if ($bbio->voltage_id == 6) $voltage = '6кВ';
                else if ($bbio->voltage_id == 10) $voltage = '10кВ';
                else $voltage = '0,4кВ';
                if (!@$data[$voltage]) {
                    @$data[$voltage] = [];
                }
                if ($bbio->classname()->get()->count()) $className = $bbio->classname()->get()->get(0)->classname;
                else $className = '';
                $busbarObject = ['busbursection' => ['type' => $className, 'name' => $bbio->name, 'id' => $busBarSection->id], 'terminals' => []];
                foreach ($busBarSection->terminal()->get() as $terminal) {
                    /** @var Identifiedobject $tio */
                    /** @var Terminal $terminal */
                    $tio = $terminal->identifiedobject()->get()->get(0);
                    $addTerminal = [];
                    $addTerminal['id'] = $terminal->id;
                    $addTerminal['voltageLevel'] = $voltage;
                    $addTerminal['name'] = $tio->name;
                    $addTerminal['busBarConnectionDotNumber'] = $tio->localname;
                    $connections = $terminal->getConnectedTerminals();
                    if ($connections) {
                        foreach ($connections as $connection) {
                            if ($connection instanceof Tower) {
                                $span = Span::where(function ($query) use ($connection) {
                                    $query->where('startIO_id', '=', $connection->identifiedobject_id)
                                        ->orWhere('endIO_id', '=', $connection->identifiedobject_id);
                                });
                                if ($span->get()->count()) {
                                    $acLIneSegmentId = $span->get()->get(0)->aclinesegment_id;
                                    $addTerminal['connected'] = true;
                                    if ($acLIneSegmentId) {
                                        $acLineSegment = Aclinesegment::find($acLIneSegmentId);
                                        if ($acLineSegment && $acLineSegment->acline_id) {
                                            /** @var Acline $acLine */
                                            $acLine = Acline::find($acLineSegment->acline_id);
                                            $addTerminal['connectedLineId'] = $acLine->id;
                                            $addTerminal['connectedLineName'] = $acLine->identifiedobject()->get()->get(0)->name;
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    $busbarObject['terminals'][] = $addTerminal;
                }
                $data[$voltage][] = $busbarObject;
            }
        }
        $equipment = [];
        $equipmentsIO = Identifiedobject::whereEnobjId($substation->identifiedobject_id)->where("classname_id" , '>', 0)->with(Asset::class)->with(Classname::class)->getModels();
        /** @var Identifiedobject $equipmentModel */
        foreach ($equipmentsIO as $equipmentModel) {
            if(!@$equipment[$equipmentModel->classname->classname]) {
                $equipment[$equipmentModel->classname->classname] = [];
            }
            $voltage = '0,4кВ';
            if($equipmentModel->voltage_id == 6) $voltage = '6кВ';
            if($equipmentModel->voltage_id == 10) $voltage = '10кВ';
            $add = [
                'IdentifiedObject' => [
                    'id' => $equipmentModel->id,
                    'className' => $equipmentModel->classname->classname,
                    'name' => $equipmentModel->getName(),
                    'keyLink' => $equipmentModel->keylink,
                    'enobj_id' => $equipmentModel->enobj_id,
                    'voltage' => $voltage,
                    'description' => $equipmentModel->getDescription()
                ]
            ];
            if($equipmentModel->asset) {
                $add['Asset'] = [
                    'id' => $equipmentModel->asset->id,
                    'assetinfokey' => $equipmentModel->asset->assetinfokey
                ];
            }
            $equipment[$equipmentModel->classname->classname][] = $add;
        }

        $dataReturn = ['equipment' =>  $equipment, 'data' => $data];
        //$dataReturn = ['equipment' => $equipment, 'data' => []];
        return view('backend.substation.parse', ['content' => $substation, 'substation' => $substation, 'data' => urlencode(json_encode($dataReturn, JSON_HEX_QUOT))]);
    }

    // ------------------------------------------------------------------
    protected function getObjectsToBusbarSectionByAclineSegment(DOMXPath $xpath, DOMNode $aclineSegment)
    {
        $returnObjects = [];
        $terminalNodes = [];
        $allTerminalsNodes = $xpath->query('//TechData[@terminals]');
        $acLineTerminals = $aclineSegment->getAttribute('terminals');
        foreach ($allTerminalsNodes as $terminalsNode) {
            $terminalsOfNode = $terminalsNode->getAttribute('terminals');
            if ($terminalsOfNode != $acLineTerminals) {
                if (@$terminalNodes[$terminalsOfNode]) {
                    if ($terminalNodes[$terminalsOfNode]->getAttribute('className') == 'Junction') {
                        $terminalNodes[$terminalsOfNode] = $terminalsNode;
                    }
                } else {
                    $terminalNodes[$terminalsOfNode] = $terminalsNode;
                }

            }
        }
        $depth = 10;
        $acLineTerminal = explode(' ', $acLineTerminals)[0];
        $founded = [$acLineTerminal];
        $terminal = $acLineTerminal;
        while ($depth--) {
            $object = $this->searchNextConnectedTerminalToTerminal(intval($terminal), $terminalNodes, $founded);
            if (!$object) break;
            else {
                $beforeObject = $object[1];
                $terminal = $object[0];
                $founded[] = $object[0];
                $returnObjects[] = $object[1];
                $object = null;
            }
        }
        return $returnObjects;
    }

    // ------------------------------------------------------------------
    protected function searchNextConnectedTerminalToTerminal(int $terminal, array $terminals, &$founded = [], $reverse = 0)
    {
        if ($terminal == 0 || $terminal == -1) return null;
        $object = null;
        $keys = array_keys($terminals);
        $isJunction = false;
        if (@$terminals[$terminal] && (
                $terminals[$terminal]->getAttribute('className') == 'BusBarSection' ||
                $terminals[$terminal]->getAttribute('className') == 'TransformerWinding'
            )) {
            return [0, $terminals[$terminal]];
        }
        foreach ($keys as $key) {
            $terminalsKeys = explode(' ', $key);
            if (@$terminalsKeys[$reverse ? 1 : 0] == $terminal
                && @$terminalsKeys[1] != -1
                && @$terminalsKeys[$reverse ? 0 : 1]
                && !in_array(@$terminalsKeys[$reverse ? 0 : 1], $founded)
                && !($terminals[$key]->getAttribute('className') == 'ACLineSegment' && $terminals[$key]->parentNode->getAttribute('className') == 'VoltageLevel')
            ) {
                return [@$terminalsKeys[$reverse ? 0 : 1], $terminals[$key]];
            } else if (@$terminalsKeys[$reverse ? 1 : 0] == $terminal && (!in_array(@$terminalsKeys[$reverse ? 0 : 1], $founded) && @$terminalsKeys[$reverse ? 0 : 1]) && (!in_array(@$terminalsKeys[0], $founded) && !@$terminalsKeys[1])) {
                if ($terminals[$key]->getAttribute('className') == 'Junction') {
                    $founded[] = $terminal;
                    $isJunction = true;
                    if (@!$terminalsKeys[1]) return $this->searchNextConnectedTerminalToTerminal($terminal, $terminals, $founded, 0);
                    return $this->searchNextConnectedTerminalToTerminal($terminal, $terminals, $founded, 1);
                } else if ($terminals[$key]->getAttribute('className') == 'BusBarSection' ||
                    $terminals[$key]->getAttribute('className') == 'TransformerWinding'
                ) {
                    return [0, $terminals[$key]];
                }
            }
        }
        if (!$reverse) return $this->searchNextConnectedTerminalToTerminal($terminal, $terminals, $founded, 1);
        return $object;
    }

    // ------------------------------------------------------------------
    protected function getConductingEquipmentForTerminal(DOMNode $terminal, DOMXPath $xpath)
    {
        if (!$this->origins) {
            $conductingEquipments = $xpath->query('//Type[@ObjectType="точка"]/SDE[@fMakeType="bussed_link"]');
            foreach ($conductingEquipments as $conductingEquipment) {
                $this->origins[] = [explode(' ', $conductingEquipment->getAttribute('origin'))[0], $conductingEquipment];
            }
        }
        $rtid = $terminal->getAttribute('RTID');
        $points = $xpath->query('//SDE[@Tech="' . $rtid . '"]')->item(0);
        if ($points) {
            if ($points->getAttribute('points')) $xCoordinate = explode(' ', explode(',', $points->getAttribute('points'))[0])[0];
            else $xCoordinate = explode(' ', explode(',', $points->getAttribute('origin'))[0])[0];

        }
        foreach ($this->origins as $sde) {
            if ($xCoordinate == $sde[0]) {
                $ce = $xpath->query('//TechData[@RTID="' . $sde[1]->getAttribute('Tech') . '"]');
                if ($ce->count()) {
                    $ce = $ce->item(0);
                    if ($ce->getAttribute('voltage') == $terminal->getAttribute('voltage') && $ce->getAttribute('fDispNum')) {
                        return str_replace('&', '', $ce->getAttribute('fDispNum'));
                    }
                }
            }
        }
        return 0;
    }
}
