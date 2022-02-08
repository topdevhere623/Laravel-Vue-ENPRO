<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\TapeShieldCableInfo;
use App\Models\CableInfo;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TapeShieldCableInfoController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = TapeShieldCableInfo::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('CableInfo', function($query) use ($request){
    $query->whereHas('WireInfo', function($query) use ($request){
    $query->whereHas('AssetInfo', function($query) use ($request){
    $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

});

});

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $TapeShieldCableInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return TapeShieldCableInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($TapeShieldCableInfoRecords, 'TapeShieldCableInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = TapeShieldCableInfo::findOrFail($id);
        $dto = TapeShieldCableInfoDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'TapeShieldCableInfo retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var TapeShieldCableInfo $TapeShieldCableInfo */
        $model = TapeShieldCableInfo::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(TapeShieldCableInfoDTO::instance()->loadFull($model), 'TapeShieldCableInfo stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var TapeShieldCableInfo $model */
        $model = TapeShieldCableInfo::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(TapeShieldCableInfoDTO::instance()->loadFull($model), 'TapeShieldCableInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var TapeShieldCableInfo $model */
        $model = TapeShieldCableInfo::findOrFail($id);
        TapeShieldCableInfo::destroy($id);
        return $this->sendSuccess("TapeShieldCableInfo deleted successfully");
    }

    public function indexView()
    {
        return view('backend.tapeshieldcableinfo.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.tapeshieldcableinfo.edit', ["id" => $request->id]);
        }
        return view('backend.tapeshieldcableinfo.edit');
    }

}

