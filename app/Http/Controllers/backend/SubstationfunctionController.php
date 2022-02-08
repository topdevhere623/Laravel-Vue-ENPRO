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
use App\Models\Substationfunction;
use App\Models\Identifiedobject;

// контроллер модели
class SubstationfunctionController extends Controller
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
        $content = Substationfunction::paginate($paginate);

        // открыть вюшку
        return view('backend.substationfunction.index', compact('content'));
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Substationfunction::findOrFail($id);
        } else {
            $content = new Substationfunction;
        }

        // справочники и другие дополнительные сведения
        $identifiedobjects = Identifiedobject::all();

        // открыть вьюшку
        return view('backend.substationfunction.edit', compact('content', 'identifiedobjects'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $bool = $this->commonCrudService->store('Substationfunction', $id, $request);
        // редирект
        return redirect(route('substationfunction.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Substationfunction', $id);
        // редирект
        return redirect()->back();
    }
}