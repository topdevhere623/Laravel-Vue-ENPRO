<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\StreetAddress;
use App\Models\StreetDetail;
use App\Models\TownDetail;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class StreetAddressController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = StreetAddress::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $StreetAddressRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return StreetAddressDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($StreetAddressRecords, 'StreetAddress retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = StreetAddress::findOrFail($id);
        $dto = StreetAddressDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'StreetAddress retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var StreetAddress $StreetAddress */
        $model = StreetAddress::create($input);

        return $this->sendResponse(StreetAddressDTO::instance()->loadFull($model), 'StreetAddress stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var StreetAddress $model */
        $model = StreetAddress::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(StreetAddressDTO::instance()->loadFull($model), 'StreetAddress updated successfully');
    }

    public function destroy($id)
    {
        /** @var StreetAddress $model */
        $model = StreetAddress::findOrFail($id);
        StreetAddress::destroy($id);
        return $this->sendSuccess("StreetAddress deleted successfully");
    }

    public function indexView()
    {
        return view('backend.streetaddress.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.streetaddress.edit', ["id" => $request->id]);
        }
        return view('backend.streetaddress.edit');
    }

}

