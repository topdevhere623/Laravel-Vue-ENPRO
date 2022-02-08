<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\AssetInfo;
use App\Models\ProductAssetModel;
use App\Models\IdentifiedObject;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AssetInfoController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = AssetInfo::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $AssetInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return AssetInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($AssetInfoRecords, 'AssetInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = AssetInfo::findOrFail($id);
        $dto = AssetInfoDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'AssetInfo retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var AssetInfo $AssetInfo */
        $model = AssetInfo::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(AssetInfoDTO::instance()->loadFull($model), 'AssetInfo stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var AssetInfo $model */
        $model = AssetInfo::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(AssetInfoDTO::instance()->loadFull($model), 'AssetInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var AssetInfo $model */
        $model = AssetInfo::findOrFail($id);
        AssetInfo::destroy($id);
        return $this->sendSuccess("AssetInfo deleted successfully");
    }

    public function indexView()
    {
        return view('backend.assetinfo.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.assetinfo.edit', ["id" => $request->id]);
        }
        return view('backend.assetinfo.edit');
    }

}

