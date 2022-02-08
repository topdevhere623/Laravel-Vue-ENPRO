<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\ProductAssetModel;
use App\Models\AssetModelUsageKind;
use App\Models\Manufacturer;
use App\Models\CorporateStandardKind;
use App\Models\Length;
use App\Models\Mass;
use App\Models\IdentifiedObject;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductAssetModelController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = ProductAssetModel::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $ProductAssetModelRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return ProductAssetModelDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($ProductAssetModelRecords, 'ProductAssetModel retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = ProductAssetModel::findOrFail($id);
        $dto = ProductAssetModelDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'ProductAssetModel retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var ProductAssetModel $ProductAssetModel */
        $model = ProductAssetModel::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ProductAssetModelDTO::instance()->loadFull($model), 'ProductAssetModel stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var ProductAssetModel $model */
        $model = ProductAssetModel::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ProductAssetModelDTO::instance()->loadFull($model), 'ProductAssetModel updated successfully');
    }

    public function destroy($id)
    {
        /** @var ProductAssetModel $model */
        $model = ProductAssetModel::findOrFail($id);
        ProductAssetModel::destroy($id);
        return $this->sendSuccess("ProductAssetModel deleted successfully");
    }

    public function indexView()
    {
        return view('backend.productassetmodel.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.productassetmodel.edit', ["id" => $request->id]);
        }
        return view('backend.productassetmodel.edit');
    }

}

