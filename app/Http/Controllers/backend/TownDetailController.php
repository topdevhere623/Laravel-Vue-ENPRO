<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\TownDetail;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TownDetailController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = TownDetail::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $TownDetailRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return TownDetailDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($TownDetailRecords, 'TownDetail retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = TownDetail::findOrFail($id);
        $dto = TownDetailDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'TownDetail retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var TownDetail $TownDetail */
        $model = TownDetail::create($input);

        return $this->sendResponse(TownDetailDTO::instance()->loadFull($model), 'TownDetail stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var TownDetail $model */
        $model = TownDetail::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(TownDetailDTO::instance()->loadFull($model), 'TownDetail updated successfully');
    }

    public function destroy($id)
    {
        /** @var TownDetail $model */
        $model = TownDetail::findOrFail($id);
        TownDetail::destroy($id);
        return $this->sendSuccess("TownDetail deleted successfully");
    }

    public function indexView()
    {
        return view('backend.towndetail.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.towndetail.edit', ["id" => $request->id]);
        }
        return view('backend.towndetail.edit');
    }

}

