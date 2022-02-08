<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\Procedure;
use App\Models\Document;
use App\Models\EnproDefect;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\DTO\ProcedureDTO;

class ProcedureController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Procedure::query();
        if (! empty($request->get('search'))) {
            $query->whereHas('Document', function($query) use ($request){
                $query->whereHas('IdentifiedObject', function($query) use ($request){
                    $query->where('identifiedobject.name', 'like', '%' . $request->get('search') .'%');
                });
            });
        }

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $ProcedureRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return ProcedureDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($ProcedureRecords, 'Procedure retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Procedure::findOrFail($id);
        $dto = ProcedureDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'Procedure retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var Procedure $Procedure */
        $model = Procedure::create($input);
        return $this->sendResponse(ProcedureDTO::instance()->loadFull($model), 'Procedure stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var Procedure $model */
        $model = Procedure::findOrFail($id);
        $input = $request->all();
        $model->update($input);
        return $this->sendResponse(ProcedureDTO::instance()->loadFull($model), 'Procedure updated successfully');
    }

    public function destroy($id)
    {
        /** @var Procedure $model */
        $model = Procedure::findOrFail($id);
        Procedure::destroy($id);
        return $this->sendSuccess("Procedure deleted successfully");
    }

    public function indexView()
    {
        return view('backend.procedure.index');
    }

    public function editView($id = null)
    {
        if($id){
            return view('backend.procedure.edit', ["id" => $id]);
        }
        return view('backend.procedure.edit');
    }

}

