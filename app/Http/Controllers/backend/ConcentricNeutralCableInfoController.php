<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\ConcentricNeutralCableInfo;
use App\Models\Length;
use App\Models\ResistancePerLength;
use App\Models\CableInfo;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ConcentricNeutralCableInfoController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = ConcentricNeutralCableInfo::query();
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
        $ConcentricNeutralCableInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return ConcentricNeutralCableInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($ConcentricNeutralCableInfoRecords, 'ConcentricNeutralCableInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = ConcentricNeutralCableInfo::findOrFail($id);
        $dto = ConcentricNeutralCableInfoDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'ConcentricNeutralCableInfo retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var ConcentricNeutralCableInfo $ConcentricNeutralCableInfo */
        $model = ConcentricNeutralCableInfo::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ConcentricNeutralCableInfoDTO::instance()->loadFull($model), 'ConcentricNeutralCableInfo stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var ConcentricNeutralCableInfo $model */
        $model = ConcentricNeutralCableInfo::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ConcentricNeutralCableInfoDTO::instance()->loadFull($model), 'ConcentricNeutralCableInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var ConcentricNeutralCableInfo $model */
        $model = ConcentricNeutralCableInfo::findOrFail($id);
        ConcentricNeutralCableInfo::destroy($id);
        return $this->sendSuccess("ConcentricNeutralCableInfo deleted successfully");
    }

    public function indexView()
    {
        return view('backend.concentricneutralcableinfo.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.concentricneutralcableinfo.edit', ["id" => $request->id]);
        }
        return view('backend.concentricneutralcableinfo.edit');
    }

}

