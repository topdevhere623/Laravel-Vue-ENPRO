<?php

namespace App\Http\Controllers\backend;

use App\DTO\PotentialTransformerInfoDTO;
use App\Helpers\UnitsMultipliersHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Services\backend\CommonService;
use App\Models\PotentialTransformerInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PotentialTransformerInfoController extends AppBaseController
{
    /* @var CommonService $commonService */
    protected $commonService;

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = PotentialTransformerInfo::query();

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
                return PotentialTransformerInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];

        return $this->sendResponse($result, 'PotentialTransformerInfo retrieved successfully', $meta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(int $id)
    {
        $model = PotentialTransformerInfo::findOrFail($id);
        $dto = PotentialTransformerInfoDTO::instance()->load($model);
        return $this->sendResponse($dto, 'PotentialTransformerInfo retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input = ['PotentialTransformerInfo' => $input];
        UnitsMultipliersHelper::mergeArray($input, ['PotentialTransformerInfo']);
        $model = PotentialTransformerInfo::create($input['PotentialTransformerInfo']);
        return $this->sendResponse(PotentialTransformerInfoDTO::instance()->load($model), 'PotentialTransformerInfo retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $input = ['PotentialTransformerInfo' => $input];
        UnitsMultipliersHelper::mergeArray($input, ['PotentialTransformerInfo']);
        $model = PotentialTransformerInfo::findOrFail($id);
        $model->update($input['PotentialTransformerInfo']);
        return $this->sendResponse(PotentialTransformerInfoDTO::instance()->load($model), 'PotentialTransformerInfo retrieved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        PotentialTransformerInfo::destroy($id);
        return $this->sendSuccess("PotentialTransformerInfo deleted successfully");
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
