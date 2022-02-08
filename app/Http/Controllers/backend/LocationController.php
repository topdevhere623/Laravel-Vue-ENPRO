<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\Location;
use App\Models\IdentifiedObject;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LocationController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Location::query();
        if (! empty($request->get('filterName'))) {
            $query->whereHas('IdentifiedObject', function($query) use ($request){
    $query->where('identifiedobject.name', 'like', '%' . $request->get('filterName') .'%');

});

        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $LocationRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return LocationDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($LocationRecords, 'Location retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Location::findOrFail($id);
        $dto = LocationDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'Location retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var Location $Location */
        $model = Location::create($input);
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(LocationDTO::instance()->loadFull($model), 'Location stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var Location $model */
        $model = Location::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();
        $IdentifiedObject = $model->getIdentifiedObject();
        $IdentifiedObject->name = $input->name;
        $IdentifiedObject->save();

        return $this->sendResponse(LocationDTO::instance()->loadFull($model), 'Location updated successfully');
    }

    public function destroy($id)
    {
        /** @var Location $model */
        $model = Location::findOrFail($id);
        Location::destroy($id);
        return $this->sendSuccess("Location deleted successfully");
    }

    public function indexView()
    {
        return view('backend.location.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.location.edit', ["id" => $request->id]);
        }
        return view('backend.location.edit');
    }

}

