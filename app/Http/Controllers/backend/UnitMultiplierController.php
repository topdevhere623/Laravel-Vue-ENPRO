<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\UnitMultiplier;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UnitMultiplierController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = UnitMultiplier::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $UnitMultiplierRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return UnitMultiplierDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($UnitMultiplierRecords, 'UnitMultiplier retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = UnitMultiplier::findOrFail($id);
        $dto = UnitMultiplierDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'UnitMultiplier retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var UnitMultiplier $UnitMultiplier */
        $model = UnitMultiplier::create($input);

        return $this->sendResponse(UnitMultiplierDTO::instance()->loadFull($model), 'UnitMultiplier stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var UnitMultiplier $model */
        $model = UnitMultiplier::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(UnitMultiplierDTO::instance()->loadFull($model), 'UnitMultiplier updated successfully');
    }

    public function destroy($id)
    {
        /** @var UnitMultiplier $model */
        $model = UnitMultiplier::findOrFail($id);
        UnitMultiplier::destroy($id);
        return $this->sendSuccess("UnitMultiplier deleted successfully");
    }

    public function indexView()
    {
        return view('backend.unitmultiplier.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.unitmultiplier.edit', ["id" => $request->id]);
        }
        return view('backend.unitmultiplier.edit');
    }

}

