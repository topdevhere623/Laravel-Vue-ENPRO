<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\CableInfo;
use App\Models\CableConstructionKind;
use App\Models\Length;
use App\Models\Temperature;
use App\Models\CableOuterJacketKind;
use App\Models\CableShieldMaterialKind;
use App\Models\WireInfo;
use App\DTO\CableInfoDTO;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CableInfoController extends AppBaseController
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
        $query = CableInfo::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('WireInfo', function($query) use ($request){
                 $query->whereHas('AssetInfo', function($query) use ($request){
                    $query->whereHas('IdentifiedObject', function($query) use ($request){
                        $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');
                    });
                });
            });
        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $CableInfoRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return CableInfoDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($CableInfoRecords, 'CableInfo retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = CableInfo::findOrFail($id);
        $dto = CableInfoDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'CableInfo retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var CableInfo $CableInfo */
        $model = CableInfo::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(CableInfoDTO::instance()->loadFull($model), 'CableInfo stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var CableInfo $model */
        $model = CableInfo::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(CableInfoDTO::instance()->loadFull($model), 'CableInfo updated successfully');
    }

    public function destroy($id)
    {
        /** @var CableInfo $model */
        $model = CableInfo::findOrFail($id);
        CableInfo::destroy($id);
        return $this->sendSuccess("CableInfo deleted successfully");
    }

    public function indexView()
    {
        return view('backend.cableinfo.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.cableinfo.edit', ["id" => $request->id]);
        }
        return view('backend.cableinfo.edit');
    }

}

