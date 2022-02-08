<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests\backend\CreateWireAssemblyInfoRequest;
use App\Http\Services\backend\CommonService;
use App\Models\Voltage;
use App\Models\WireAssemblyInfo;
use App\Models\Length;
use App\Models\WireInsulationKind;
use App\Models\WireMaterialKind;
use App\Models\ResistancePerLength;
use App\Models\CurrentFlow;
use App\Models\AssetInfo;
use App\DTO\WireAssemblyInfoDTO;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Requests\backend\CreateWireInfoRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\UnitsMultipliersHelper;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;

class WireAssemblyInfoController extends AppBaseController
{
    /* @var CommonService $commonService */
    protected $commonService;

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request, $modelName)
    {
        $query = WireAssemblyInfo::query();

        $query->whereHas('WirePhaseInfo', function($query) use ($modelName){
            $query->whereHas('WireInfo', function($query) use ($modelName){
                $query->whereHas($modelName);
            });
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
        $query->when(! (empty(request('nominalVoltageMin')) && empty(request('nominalVoltageMax'))), function ($q) use (&$filters) {
            if (! empty(request('nominalVoltageMin'))) $filters['nominalVoltageMin'] = request('nominalVoltageMin');
            if (! empty(request('nominalVoltageMax'))) $filters['nominalVoltageMax'] = request('nominalVoltageMax');
            $q->whereHas('WirePhaseInfo', function($q) {
                $q->whereHas('WireInfo', function($q) {
                    $q->whereHas('nominalVoltage', function($q) {
                        if (! empty(request('nominalVoltageMin'))) {
                            $q->where('value', '>=', request('nominalVoltageMin'));
                        }
                        if (! empty(request('nominalVoltageMax'))) {
                            $q->where('value', '<=', request('nominalVoltageMax'));
                        }
                    });
                });
            });
        });
        $query->when(! empty(request('materialIds')), function ($q) use (&$filters) {
            $filters['materialIds'] = request('materialIds');
            $q->whereHas('WirePhaseInfo', function($q) {
                $q->whereHas('WireInfo', function($q) {
                    $q->whereIn('material_id', request('materialIds'));
                });
            });
        });
        $query->when(! empty(request('insulationMaterialIds')), function ($q) use (&$filters) {
            $filters['insulationMaterialIds'] = request('insulationMaterialIds');
            $q->whereHas('WirePhaseInfo', function($q) {
                $q->whereHas('WireInfo', function($q) {
                    $q->whereIn('insulation_material_id', request('insulationMaterialIds'));
                });
            });
        });
        $query->when(request()->has('isInsulated'), function ($q) use (&$filters) {
            $filters['isInsulated'] = request('isInsulated');
            $q->whereHas('WirePhaseInfo', function($q) {
                $q->where(function($q) {
                    $q->whereHas('phaseInfo', function($q) {
                        $q->where('literal', '=', 'A');
                    })
                    ->orWhereNull('phase_info_id');
                })
                ->whereHas('WireInfo', function($q) {
                    $q->where('insulated', '=', (bool)request('isInsulated'));
                });
            });
        });
        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $WireInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return WireAssemblyInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];
        return $this->sendResponse($WireInfoRecords, 'WireInfo retrieved successfully', $meta);
    }

    public function show($modelName, $id)
    {
        $model = WireAssemblyInfo::findOrFail($id);
        $dto = WireAssemblyInfoDTO::instance()->load($model);
        $meta['kindModels'] = WireAssemblyInfo::KIND_MODELS;
        return $this->sendResponse($dto, 'WireInfo retrieved successfully');
    }

    public function store(CreateWireAssemblyInfoRequest $request, $modelName)
    {
        $input = $request->all();
        UnitsMultipliersHelper::mergeArray($input, ['WireInfo', 'CableInfo']);
        /** @var WireAssemblyInfo $model */
        $model = WireAssemblyInfo::create($input);

        return $this->sendResponse(WireAssemblyInfoDTO::instance()->load($model), 'WireInfo stored successfully');
    }

    public function update(Request $request, $modelName, $id)
    {
        /** @var WireAssemblyInfo $model */
        $model = WireAssemblyInfo::findOrFail($id);
        $input = $request->all();
        UnitsMultipliersHelper::mergeArray($input, ['WireInfo', 'CableInfo']);
        $model->update($input);
        return $this->sendResponse(WireAssemblyInfoDTO::instance()->load($model), 'WireInfo updated successfully');
    }

    public function destroy($modelName, $id)
    {
        /** @var WireAssemblyInfo $model */
        $model = WireAssemblyInfo::findOrFail($id);
        $model->delete();
        return $this->sendSuccess("WireInfo deleted successfully");
    }

    public function indexViewCableInfo()
    {
        return view('backend.cable_info.index');
    }

    public function editViewCableInfo($id = null)
    {
        if($id){
            return view('backend.cable_info.edit', ["id" => $id, 'kindModels' => WireAssemblyInfo::KIND_MODELS, 'enumKindModels' => WireAssemblyInfo::ENUM_KIND_MODELS]);
        }
        return view('backend.cable_info.edit', ['kindModels' => WireAssemblyInfo::KIND_MODELS, 'enumKindModels' => WireAssemblyInfo::ENUM_KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function indexViewOverheadWireInfo()
    {
        return view('backend.overhead_wire_info.index');
    }

    public function editViewOverheadWireInfo($id = null)
    {
        if($id){
            return view('backend.overhead_wire_info.edit', ["id" => $id, 'kindModels' => WireAssemblyInfo::KIND_MODELS, 'enumKindModels' => WireAssemblyInfo::ENUM_KIND_MODELS]);
        }
        return view('backend.overhead_wire_info.edit', ['kindModels' => WireAssemblyInfo::KIND_MODELS, 'enumKindModels' => WireAssemblyInfo::ENUM_KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }

    public function clearWireAssemblyInfo(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('set_time_limit', '3600');
        ini_set('max_execution_time', '3600');
        $this->deleteRows('OverheadWireInfo');
        $this->deleteRows('CableInfo');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WireAssemblyInfo::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return true;
    }

    public function importOverheadWireInfo(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('set_time_limit', '3600');
        ini_set('max_execution_time', '3600');
        $this->deleteRows('OverheadWireInfo');
        $getFile = $request['file'];
        $excelImportName = "App\\Imports\\OverheadWireInfoImport";
        Excel::import(new $excelImportName, $getFile);
        return true;
    }

    public function importCableInfo(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('set_time_limit', '3600');
        ini_set('max_execution_time', '3600');
        $this->deleteRows('CableInfo');
        $getFile = $request['file'];
        $excelImportName = "App\\Imports\\CableInfoImport";
        Excel::import(new $excelImportName, $getFile);
        return true;
    }

    private function deleteRows($modelName)
    {
        $ids = WireAssemblyInfo::whereHas('WirePhaseInfo', function($query) use ($modelName){
            $query->whereHas('WireInfo', function($query) use ($modelName){
                $query->whereHas($modelName);
            });
        })
            ->pluck('id')
            ->toArray();
        WireAssemblyInfo::whereIn('id', $ids)->forceDelete();
        //WireInfo::query()->forceDelete();
        //WireMaterialKind::query()->forceDelete();
        //WireInsulationKind::query()->forceDelete();
    }

    public function massDestroy($modelName)
    {
        WireAssemblyInfo::whereIn('id', request()->get('ids'))
            ->whereHas('WirePhaseInfo', function($query) use ($modelName){
                $query->whereHas('WireInfo', function($query) use ($modelName){
                        $query->whereHas($modelName);
                    });
                })
            ->delete();
        return $this->sendSuccess("WireAssemblyInfo deleted successfully");
    }

}

