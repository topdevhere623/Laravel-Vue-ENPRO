<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\StreetDetail;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class StreetDetailController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = StreetDetail::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $StreetDetailRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return StreetDetailDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($StreetDetailRecords, 'StreetDetail retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = StreetDetail::findOrFail($id);
        $dto = StreetDetailDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'StreetDetail retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var StreetDetail $StreetDetail */
        $model = StreetDetail::create($input);

        return $this->sendResponse(StreetDetailDTO::instance()->loadFull($model), 'StreetDetail stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var StreetDetail $model */
        $model = StreetDetail::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(StreetDetailDTO::instance()->loadFull($model), 'StreetDetail updated successfully');
    }

    public function destroy($id)
    {
        /** @var StreetDetail $model */
        $model = StreetDetail::findOrFail($id);
        StreetDetail::destroy($id);
        return $this->sendSuccess("StreetDetail deleted successfully");
    }

    public function indexView()
    {
        return view('backend.streetdetail.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.streetdetail.edit', ["id" => $request->id]);
        }
        return view('backend.streetdetail.edit');
    }

}

