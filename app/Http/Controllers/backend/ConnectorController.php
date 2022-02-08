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
use App\Models\Connector;
use App\Models\BaseVoltage;
use App\Models\Asset;
use App\Models\Identifiedobject;

// контроллер модели
class ConnectorController extends Controller
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
        $content = Connector::with('endpoint', 'asset', 'identifiedobject')->paginate($paginate);

        // открыть вюшку
        return view('backend.connector.index', compact('content'));
    }

    // вывод одной строки
    public function edit(Request $request, $id = null)
    {
        // проверить параметры, возможно переданные через post запрос
        $thisModal = $request->input('thisModal');

        // контент
        if ($id) {
            $content = Connector::findOrFail($id);
        } else {
            $content = new Connector;
        }

        // справочники и другие дополнительные сведения
        $basevoltages = BaseVoltage::all();
        $assets = Asset::all();
        $identifiedobjects = Identifiedobject::all();

        // возвращаемый параметр
        if (is_null($thisModal)) {
            // открыть вьюшку
            return view('backend.connector.edit', compact('content', 'basevoltages', 'assets', 'identifiedobjects'));
        } else {
            // вернуть готовый рендер страницы
            $html = view('backend.connector.editcontent')->with(
                [
                    'thisModal' => $thisModal,
                    'content' => $content,
                    'basevoltages' => $basevoltages,
                    'assets' => $assets,
                    'identifiedobjects' => $identifiedobjects,
                ])->render();
            return response()->json(['html' => $html, 'code' => 200]);
        }
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        // проверить параметр модального окна
        $thisModal = $request->input('thisModal');

        $model = $this->commonCrudService->store('Connector', $id, $request, 'img');

        if (is_null($thisModal)) {
            // редирект
            return redirect(route('connector.index'));
        } else {
            // далее закрытие модального окна
            $id = $model->id;
            $model = Connector::find($id); // почемму то $model не может получить свое имя из IO
            $myName = $model->identifiedobject->name;
            return response()->json(['id' => $id, 'name' => $myName]);
        }
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Connector', $id);
        // редирект
        return redirect()->back();
    }
}
