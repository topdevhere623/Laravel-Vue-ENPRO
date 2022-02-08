<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\CurrentFlow;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CurrentFlowController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = CurrentFlow::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $CurrentFlowRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return CurrentFlowDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($CurrentFlowRecords, 'CurrentFlow retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = CurrentFlow::findOrFail($id);
        $dto = CurrentFlowDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'CurrentFlow retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var CurrentFlow $CurrentFlow */
        $model = CurrentFlow::create($input);

        return $this->sendResponse(CurrentFlowDTO::instance()->loadFull($model), 'CurrentFlow stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var CurrentFlow $model */
        $model = CurrentFlow::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(CurrentFlowDTO::instance()->loadFull($model), 'CurrentFlow updated successfully');
    }

    public function destroy($id)
    {
        /** @var CurrentFlow $model */
        $model = CurrentFlow::findOrFail($id);
        CurrentFlow::destroy($id);
        return $this->sendSuccess("CurrentFlow deleted successfully");
    }

    public function indexView()
    {
        return view('backend.currentflow.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.currentflow.edit', ["id" => $request->id]);
        }
        return view('backend.currentflow.edit');
    }

}

