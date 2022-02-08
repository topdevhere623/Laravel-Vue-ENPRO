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
use App\Models\Tower;
use App\Models\Identifiedobject;

// контроллер модели
class TowerController extends Controller
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
        $content = Tower::with('identifiedobject', 'towermaterial', 'towerinfo', 'towerkind', 'towerconstruction', 'towerinfo')->paginate($paginate);

        // открыть вюшку
        return view('backend.tower.index', compact('content'));
    }

    // вывод одной строки
    public function edit(Request $request, $id = null)
    {
        // контент
        if ($id) {
            $content = Tower::find($id);
        } else {
            $content = new Tower;
        }

        // справочники и другие дополнительные сведения
        $identifiedobjects = Identifiedobject::all();

        // открыть вьюшку
        return view('backend.tower.edit', compact('content', 'identifiedobjects'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $model = $this->commonCrudService->store('Tower', $id, $request);

        // редирект
        return redirect(route('tower.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Tower', $id);
        // редирект
        return redirect()->back();
    }

    // ------------------------------------------------------------------
    // сохранение данных (Vue)
    public function vueSave(Request $request)
    {
        // переданные параметры через запрос post
        $getModelID = $request['modelID'];
        $getModelData = json_decode($request['modelData'], true);

        if (isset($getModelID) and $getModelID > 0) {
            $modelSave = Tower::find($getModelID);
        } else {
            $modelSave = new Tower;
        }

        // подготовка полей для сохранение в основной таблице
        // проверка, есть ли данные в полученном массиве
        $myFields = ['towerinfo_id', 'towermaterial_id', 'towerkind_id', 'towerconstructionkind_id', 'propN', 'guy', 'strut', 'strut', 'strutN', 'annex'];
        foreach ($myFields as $item) {
            if (isset($getModelData[$item])) {
                $modelSave->$item = $getModelData[$item];
            }
        }

        // нужно в IO
//        if (isset($getModelData['name'])) {
//            $modelSave->name = $getModelData['name'];
//        }


        // сохранить
        $modelSave->save();
        // присвоенный ID, если его еще не было
        $newID = $modelSave->id;
        // заново прочитать всю строку (с новым id, статусом, датой обновления и пр.)
        $modelSave = Tower::find($newID);

        // возвращаемый параметр
        return $modelSave;
    }
}