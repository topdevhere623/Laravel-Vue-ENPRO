<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\Organisation;
use App\Models\StreetAddress;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrganisationController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Organisation::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $OrganisationRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return OrganisationDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($OrganisationRecords, 'Organisation retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Organisation::findOrFail($id);
        $dto = OrganisationDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'Organisation retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var Organisation $Organisation */
        $model = Organisation::create($input);

        return $this->sendResponse(OrganisationDTO::instance()->loadFull($model), 'Organisation stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var Organisation $model */
        $model = Organisation::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(OrganisationDTO::instance()->loadFull($model), 'Organisation updated successfully');
    }

    public function destroy($id)
    {
        /** @var Organisation $model */
        $model = Organisation::findOrFail($id);
        Organisation::destroy($id);
        return $this->sendSuccess("Organisation deleted successfully");
    }

    public function indexView()
    {
        return view('backend.organisation.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.organisation.edit', ["id" => $request->id]);
        }
        return view('backend.organisation.edit');
    }

}

