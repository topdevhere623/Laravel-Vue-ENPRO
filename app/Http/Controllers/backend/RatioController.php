<?php

namespace App\Http\Controllers\backend;

use App\DTO\RatioDTO;
use App\Http\Controllers\Controller;
use App\Http\Services\backend\CommonService;
use App\Models\Ratio;
use Illuminate\Http\Request;

class RatioController extends Controller
{
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $query = Ratio::query();

        $filters = null;

        $page = $request->get('page', 1);
        $size = $this->commonService->getAdmminSetting('setting_paginate_admin');
        $total = $query->count();
        $result = $query->offset(($page-1)*$size)
            ->limit($size)
            ->get()
            ->map(function($model) {
                return RatioDTO::instance()->load($model);
            });
        $pagination = ['page' => $page, 'size' => $size, 'total' => $total];
        $meta = ['pagination' => $pagination, 'filters'  => $filters];

        return $this->sendResponse($result, 'Ratio retrieved successfully', $meta);
    }

    public function show($id)
    {
        $model = Ratio::findOrFail($id);
        $dto = RatioDTO::instance()->load($model);
        return $this->sendResponse($dto, 'Ratio retrieved successfully');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $model = Ratio::create($input);
        return $this->sendResponse(RatioDTO::instance()->load($model), 'Ratio retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $model = Ratio::findOrFail($id);
        $model->update($input);
        return $this->sendResponse(RatioDTO::instance()->load($model), 'Ratio retrieved successfully');
    }

    public function destroy($id)
    {
        Ratio::destroy($id);
        return $this->sendSuccess("Ratio deleted successfully");
    }

    public function indexView()
    {
        return "No view file";
        //return view('backend.old_transformer_tank_info.index');
    }

    public function editView($id = null)
    {
        return "No view file";
    }
}
