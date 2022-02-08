<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\CreateOldTransformerTankInfoRequest;
use App\Models\OldTransformerTankInfo;
use Illuminate\Http\Request;
use App\Http\Services\backend\CommonService;
use App\DTO\OldTransformerTankInfoDTO;
use App\Http\Controllers\AppBaseController;

class OldTransformerTankInfoController extends AppBaseController
{

    /* @var CommonService $commonService */
    protected $commonService;

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = OldTransformerTankInfo::query();

        if (! empty($request->get('search'))) {
            $query->whereHas('TransformerTankInfo', function($query) use ($request){
                $query->whereHas('AssetInfo', function($query) use ($request){
                    $query->whereHas('CatalogAssetType', function($query) use ($request){
                        $query->whereHas('IdentifiedObject', function($query) use ($request){
                            $query->where('identifiedobject.name', 'like', '%' . $request->get('search') .'%');
                        });
                    });
                });
            });
        }
        //Фильтры
        $filters = null;
        $query->when(! empty(request('functionId')), function ($q) use (&$filters) {
            $filters['functionId'] = request('functionId');
            $q->where('function_id', '=', request('functionId'));
        });
        $query->when(! (empty(request('ratedUMin')) && empty(request('ratedUMax'))), function ($q) use (&$filters) {
            if (! empty(request('ratedUMin'))) $filters['ratedUMin'] = request('ratedUMin');
            if (! empty(request('ratedUMax'))) $filters['ratedUMax'] = request('ratedUMax');
            $q->whereHas('TransformerTankInfo', function($q) {
                $q->whereHas('TransformerEndInfo', function($q) {
                    $q->whereHas('ratedU', function($q) {
                        if (! empty(request('ratedUMin'))) {
                            $q->where('value', '>=', request('ratedUMin'));
                        }
                        if (! empty(request('ratedUMax'))) {
                            $q->where('value', '<=', request('ratedUMax'));
                        }
                    });
                });
            });
        });
        $query->when(! (empty(request('ratedSMin')) && empty(request('ratedSMax'))), function ($q) use (&$filters) {
            if (! empty(request('ratedSMin'))) $filters['ratedSMin'] = request('ratedSMin');
            if (! empty(request('ratedSMax'))) $filters['ratedSMax'] = request('ratedSMax');
            $q->whereHas('TransformerTankInfo', function($q) {
                $q->whereHas('TransformerEndInfo', function($q) {
                    $q->whereHas('ratedS', function($q) {
                        if (! empty(request('ratedSMin'))) {
                            $q->where('value', '>=', request('ratedSMin'));
                        }
                        if (! empty(request('ratedSMax'))) {
                            $q->where('value', '<=', request('ratedSMax'));
                        }
                    });
                });
            });
        });
        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $OldTransformerTankInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return OldTransformerTankInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];

        return $this->sendResponse($OldTransformerTankInfoRecords, 'OldTransformerTankInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = OldTransformerTankInfo::with('TransformerTankInfo', 'TransformerTankInfo.TransformerEndInfo')->findOrFail($id);
        $dto = OldTransformerTankInfoDTO::instance()->load($model);
        return $this->sendResponse($dto, 'OldTransformerTankInfo retrieved successfully');
    }

    public function store(CreateOldTransformerTankInfoRequest $request)
    {
        $input = $request->all();
        /** @var OldTransformerTankInfo $WireInfo */
        $model = OldTransformerTankInfo::create($input);

        return $this->sendResponse(OldTransformerTankInfoDTO::instance()->load($model), 'OldTransformerTankInfo stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var OldTransformerTankInfo $model */
        $model = OldTransformerTankInfo::findOrFail($id);
        $input = $request->all();
        $model->update($input);
        return $this->sendResponse(OldTransformerTankInfoDTO::instance()->load($model), 'OldTransformerTankInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var OldTransformerTankInfo $model */
        $model = OldTransformerTankInfo::findOrFail($id);
        OldTransformerTankInfo::destroy($id);
        return $this->sendSuccess("OldTransformerTankInfo deleted successfully");
    }


    public function indexView()
    {
        return view('backend.old_transformer_tank_info.index');
    }

    public function editView($id = null)
    {
        if($id){
            return view('backend.old_transformer_tank_info.edit', ["id" => $id, 'kindModels' => OldTransformerTankInfo::KIND_MODELS]);
        }
        return view('backend.old_transformer_tank_info.edit', ['kindModels' => OldTransformerTankInfo::KIND_MODELS, 'fromId' => request()->get('fromId')]);
    }
}
