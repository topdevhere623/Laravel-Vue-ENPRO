<?php

namespace App\Http\Controllers\backend;

use App\Helpers\UnitsMultipliersHelper;
use App\Http\Requests\backend\CreateSwitchInfoRequest;
use App\Http\Services\backend\CommonService;
use App\Models\SwitchInfo;
use App\Models\BreakerConstructionKind;
use App\Models\InterrupterPositionKind;
use App\Models\Voltage;
use App\Models\Frequency;
use App\Models\CurrentFlow;
use App\Models\Seconds;
use App\Models\Pressure;
use App\Models\EnproForce;
use App\Models\Length;
use App\Models\GostClimaticModPlacementKind;
use App\Models\TemperatureRange;
use App\Models\Gost;
use App\Models\Mass;
use App\Models\AssetInfo;
use App\DTO\SwitchInfoDTO;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class SwitchInfoController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request, $modelName)
    {
        $query = SwitchInfo::query();

        $query->whereHas('OldSwitchInfo', function($query) use ($modelName){
            $query->whereHas($modelName);
        });

        $query->when(! empty(request('search')), function ($q) {
            $q->whereHas('AssetInfo', function($q) {
                $q->whereHas('CatalogAssetType', function($q) {
                    $q->whereHas('IdentifiedObject', function($q) {
                        $q->where('identifiedobject.name', 'like', '%' . request('search') .'%');
                    });
                });
            });
        });
        //Фильтры
        $filters = null;
        $query->when(! empty(request('enproBreakerKindId')), function ($q) use (&$filters) {
            $filters['enproBreakerKindId'] = request('enproBreakerKindId');
            $q->where('enpro_breaker_kind_id', request('enproBreakerKindId'));
        });
        $query->when(! (empty(request('ratedVoltageMin')) && empty(request('ratedVoltageMax'))), function ($q) use (&$filters) {
            if (! empty(request('ratedVoltageMin'))) $filters['ratedVoltageMin'] = request('ratedVoltageMin');
            if (! empty(request('ratedVoltageMax'))) $filters['ratedVoltageMax'] = request('ratedVoltageMax');
            $q->whereHas('ratedVoltage', function($q) {
                if (! empty(request('ratedVoltageMin'))) {
                    $q->where('value', '>=', request('ratedVoltageMin'));
                }
                if (! empty(request('ratedVoltageMax'))) {
                    $q->where('value', '<=', request('ratedVoltageMax'));
                }
            });
        });
        $query->when(! (empty(request('ratedCurrentMin')) && empty(request('ratedCurrentMax'))), function ($q) use (&$filters) {
            if (! empty(request('ratedCurrentMin'))) $filters['ratedCurrentMin'] = request('ratedCurrentMin');
            if (! empty(request('ratedCurrentMax'))) $filters['ratedCurrentMax'] = request('ratedCurrentMax');
            $q->whereHas('ratedCurrent', function($q) {
                if (! empty(request('ratedCurrentMin'))) {
                    $q->where('value', '>=', request('ratedCurrentMin'));
                }
                if (! empty(request('ratedVoltageMax'))) {
                    $q->where('value', '<=', request('ratedCurrentMax'));
                }
            });
        });
        $query->when(! (empty(request('breakingCapacityMin')) && empty(request('breakingCapacityMax'))), function ($q) use (&$filters) {
            if (! empty(request('breakingCapacityMin'))) $filters['breakingCapacityMin'] = request('breakingCapacityMin');
            if (! empty(request('breakingCapacityMax'))) $filters['breakingCapacityMax'] = request('breakingCapacityMax');
            $q->whereHas('breakingCapacity', function($q) {
                if (! empty(request('breakingCapacityMin'))) {
                    $q->where('value', '>=', request('breakingCapacityMin'));
                }
                if (! empty(request('breakingCapacityMax'))) {
                    $q->where('value', '<=', request('breakingCapacityMax'));
                }
            });
        });
        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $SwitchInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) use($modelName){
                $func = "load$modelName";
                return SwitchInfoDTO::instance()->$func($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];
        return $this->sendResponse($SwitchInfoRecords, 'SwitchInfo retrieved successfully', $meta);
    }

    public function show($modelName, $id)
    {
        $model = SwitchInfo::findOrFail($id);
        $func = "load$modelName";
        $dto = SwitchInfoDTO::instance()->$func($model);
        return $this->sendResponse($dto, 'SwitchInfo retrieved successfully');
    }

    public function store(CreateSwitchInfoRequest $request, $modelName)
    {
        $input = $request->all();
        $mergedArray = ['SwitchInfo' => $input];
        UnitsMultipliersHelper::mergeArray($mergedArray, ['SwitchInfo', 'OldSwitchInfo', 'enproTemperatureRange']);
        /** @var SwitchInfo $SwitchInfo */
        $model = SwitchInfo::create($mergedArray['SwitchInfo']);
        $func = "load$modelName";
        return $this->sendResponse(SwitchInfoDTO::instance()->$func($model), 'SwitchInfo stored successfully');
    }

    public function update(CreateSwitchInfoRequest $request, $modelName, $id)
    {
        /** @var SwitchInfo $model */
        $model = SwitchInfo::findOrFail($id);
        $input = $request->all();
        $mergedArray = ['SwitchInfo' => $input];
        UnitsMultipliersHelper::mergeArray($mergedArray, ['SwitchInfo', 'OldSwitchInfo', 'enproTemperatureRange']);
        $model->update($mergedArray['SwitchInfo']);
        $func = "load$modelName";
        return $this->sendResponse(SwitchInfoDTO::instance()->$func($model), 'SwitchInfo updated successfully');
    }

    public function destroy($modelName, $id)
    {
        /** @var SwitchInfo $model */
        $model = SwitchInfo::findOrFail($id);
        SwitchInfo::destroy($id);
        return $this->sendSuccess("SwitchInfo deleted successfully");
    }

    public function indexViewBreakerInfo()
    {
        return view('backend.breaker_info.index');
    }

    public function editViewBreakerInfo($id = null)
    {
        if($id){
            return view('backend.breaker_info.edit', ["id" => $id, 'kindModels' => SwitchInfo::KIND_MODELS]);
        }
        return view('backend.breaker_info.edit', ['kindModels' => SwitchInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function indexViewRecloserInfo()
    {
        return view('backend.recloser_info.index');
    }

    public function editViewRecloserInfo($id = null)
    {
        if($id){
            return view('backend.recloser_info.edit', ["id" => $id, 'kindModels' => SwitchInfo::KIND_MODELS]);
        }
        return view('backend.recloser_info.edit', ['kindModels' => SwitchInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function indexViewFuseInfo()
    {
        return view('backend.fuse_info.index');
    }

    public function editViewFuseInfo($id = null)
    {
        if($id){
            return view('backend.fuse_info.edit', ["id" => $id, 'kindModels' => SwitchInfo::KIND_MODELS]);
        }
        return view('backend.fuse_info.edit', ['kindModels' => SwitchInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function indexViewDisconnectorInfo()

    {
        return view('backend.disconnector_info.index');
    }

    public function editViewDisconnectorInfo($id = null)
    {
        if($id){
            return view('backend.disconnector_info.edit', ["id" => $id, 'kindModels' => SwitchInfo::KIND_MODELS]);
        }
        return view('backend.disconnector_info.edit', ['kindModels' => SwitchInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function indexViewLoadBreakSwitchInfo()
    {
        return view('backend.load_break_switch_info.index');
    }

    public function editViewLoadBreakSwitchInfo($id = null)
    {
        if($id){
            return view('backend.load_break_switch_info.edit', ["id" => $id, 'kindModels' => SwitchInfo::KIND_MODELS]);
        }
        return view('backend.load_break_switch_info.edit', ['kindModels' => SwitchInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function import(Request $request, $modelName)
    {
        ini_set('memory_limit', '2048M');
        ini_set('set_time_limit', '3600');
        ini_set('max_execution_time', '3600');
        //$this->deleteRows($modelName);
        $getFile = $request['file'];
        $excelImportName = "App\\Imports\\" . $modelName . "Import";
        Excel::import(new $excelImportName, $getFile);
        return true;
    }

    private function deleteRows($modelName)
    {
        $ids = SwitchInfo::whereHas('OldSwitchInfo', function($query) use ($modelName){
            $query->whereHas($modelName);
        })
            ->pluck('id')
            ->toArray();
        SwitchInfo::whereIn('id', $ids)->forceDelete();
        //WireInfo::query()->forceDelete();
        //WireMaterialKind::query()->forceDelete();
        //WireInsulationKind::query()->forceDelete();
    }
}
