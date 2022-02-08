<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\Manufacturer;
use App\Models\OrganisationRole;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ManufacturerController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Manufacturer::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('OrganisationRole', function($query) use ($request){
    $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $ManufacturerRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return ManufacturerDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($ManufacturerRecords, 'Manufacturer retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Manufacturer::findOrFail($id);
        $dto = ManufacturerDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'Manufacturer retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var Manufacturer $Manufacturer */
        $model = Manufacturer::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ManufacturerDTO::instance()->loadFull($model), 'Manufacturer stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var Manufacturer $model */
        $model = Manufacturer::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(ManufacturerDTO::instance()->loadFull($model), 'Manufacturer updated successfully');
    }

    public function destroy($id)
    {
        /** @var Manufacturer $model */
        $model = Manufacturer::findOrFail($id);
        Manufacturer::destroy($id);
        return $this->sendSuccess("Manufacturer deleted successfully");
    }

    public function indexView()
    {
        return view('backend.manufacturer.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.manufacturer.edit', ["id" => $request->id]);
        }
        return view('backend.manufacturer.edit');
    }

}

