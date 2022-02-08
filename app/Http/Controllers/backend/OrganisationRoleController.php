<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\OrganisationRole;
use App\Models\Organisation;
use App\Models\IdentifiedObject;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrganisationRoleController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = OrganisationRole::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $OrganisationRoleRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return OrganisationRoleDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($OrganisationRoleRecords, 'OrganisationRole retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = OrganisationRole::findOrFail($id);
        $dto = OrganisationRoleDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'OrganisationRole retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var OrganisationRole $OrganisationRole */
        $model = OrganisationRole::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(OrganisationRoleDTO::instance()->loadFull($model), 'OrganisationRole stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var OrganisationRole $model */
        $model = OrganisationRole::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(OrganisationRoleDTO::instance()->loadFull($model), 'OrganisationRole updated successfully');
    }

    public function destroy($id)
    {
        /** @var OrganisationRole $model */
        $model = OrganisationRole::findOrFail($id);
        OrganisationRole::destroy($id);
        return $this->sendSuccess("OrganisationRole deleted successfully");
    }

    public function indexView()
    {
        return view('backend.organisationrole.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.organisationrole.edit', ["id" => $request->id]);
        }
        return view('backend.organisationrole.edit');
    }

}

