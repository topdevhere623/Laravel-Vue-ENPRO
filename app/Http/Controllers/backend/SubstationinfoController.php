<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Substationinfo;

// контроллер модели
class SubstationinfoController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
    }

    // вывод списка
    public function index()
    {
        // пагинация
        $paginate = $this->commonService->getAdmminSetting('setting_paginate_admin');
        // получение данных модели
        $content = Substationinfo::paginate($paginate);

        // открыть вюшку
        return view('backend.substationinfo.index', compact('content'));
    }


    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Substationinfo::findOrFail($id);
        } else {
            $content = new Substationinfo;
        }

        // справочники и другие дополнительные сведения


        // открыть вьюшку
        return view('backend.substationinfo.edit', compact('content'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $bool = $this->commonCrudService->store('Substationinfo', $id, $request);
        // редирект
        return redirect(route('substationinfo.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Substationinfo', $id);
        // редирект
        return redirect()->back();
    }
}
