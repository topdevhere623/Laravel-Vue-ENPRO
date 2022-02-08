<?php

namespace App\Http\Controllers\backend;

use App\DTO\CurrenttransformerinfoDTO;
use App\Helpers\UnitsMultipliersHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\backend\CommonService;
use App\Models\Currenttransformerinfo;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class CurrenttransformerinfoController extends AppBaseController
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
        $query = Currenttransformerinfo::query();

        if (!empty($request->get('search'))) {
            $query->whereHas('AssetInfo', function($query) use ($request){
                $query->whereHas('CatalogAssetType', function($query) use ($request){
                    $query->whereHas('IdentifiedObject', function($query) use ($request){
                        $query->where('identifiedobject.name', 'like', '%' . $request->get('search') .'%');
                    });
                });
            });
        }

        //Фильтры
        $filters = null;
        /*
        $query->when(! empty(request('functionId')), function ($q) use (&$filters) {
            $filters['functionId'] = request('functionId');
            $q->where('function_id', '=', request('functionId'));
        });
        */

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $result = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return CurrenttransformerinfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];

        return $this->sendResponse($result, 'Currenttransformerinfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Currenttransformerinfo::findOrFail($id);
        $dto = CurrenttransformerinfoDTO::instance()->load($model);
        return $this->sendResponse($dto, 'Currenttransformerinfo retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input = ['CurrentTransformerInfo' => $input];
        UnitsMultipliersHelper::mergeArray($input, ['CurrentTransformerInfo']);
        $model = Currenttransformerinfo::create($input['CurrentTransformerInfo']);
        return $this->sendResponse(CurrenttransformerinfoDTO::instance()->load($model), 'Currenttransformerinfo retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $input = ['CurrentTransformerInfo' => $input];
        UnitsMultipliersHelper::mergeArray($input, ['CurrentTransformerInfo']);
        $model = Currenttransformerinfo::findOrFail($id);
        $model->update($input['CurrentTransformerInfo']);
        return $this->sendResponse(CurrenttransformerinfoDTO::instance()->load($model), 'Currenttransformerinfo retrieved successfully');
    }

    public function destroy($id)
    {
        Currenttransformerinfo::destroy($id);
        return $this->sendSuccess("Currenttransformerinfo deleted successfully");
    }

    public function indexView()
    {
        return "No view file";
        //return view('backend.old_transformer_tank_info.index');
    }

    public function editView($id = null)
    {
        return "No view file";
    }
}
