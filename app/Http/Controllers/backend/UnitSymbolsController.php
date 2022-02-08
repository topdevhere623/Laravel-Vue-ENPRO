<?php

namespace App\Http\Controllers\backend;

use App\Http\Services\backend\CommonService;
use App\Models\UnitSymbol;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UnitSymbolsController extends AppBaseController
{

    // подключение сервисов
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = UnitSymbol::query();

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $UnitSymbolsRecords = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return UnitSymbolsDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination];
        return $this->sendResponse($UnitSymbolsRecords, 'UnitSymbols retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = UnitSymbol::findOrFail($id);
        $dto = UnitSymbolsDTO::instance()->loadFull($model);
        return $this->sendResponse($dto, 'UnitSymbols retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        /** @var UnitSymbol $UnitSymbols */
        $model = UnitSymbol::create($input);

        return $this->sendResponse(UnitSymbolsDTO::instance()->loadFull($model), 'UnitSymbols stored successfully');
    }

    public function update(Request $request, $id)
    {
        /** @var UnitSymbol $model */
        $model = UnitSymbol::findOrFail($id);
        $input = $request->all();
        $model->fill($input);
        $model->save();

        return $this->sendResponse(UnitSymbolsDTO::instance()->loadFull($model), 'UnitSymbols updated successfully');
    }

    public function destroy($id)
    {
        /** @var UnitSymbol $model */
        $model = UnitSymbol::findOrFail($id);
        UnitSymbol::destroy($id);
        return $this->sendSuccess("UnitSymbols deleted successfully");
    }

    public function indexView()
    {
        return view('backend.unitsymbols.index');
    }

    public function editView(Request $request)
    {
        if($request->has('id')){
            return view('backend.unitsymbols.edit', ["id" => $request->id]);
        }
        return view('backend.unitsymbols.edit');
    }

}

