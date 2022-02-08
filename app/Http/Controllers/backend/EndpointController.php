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
use App\Models\Endpoint;
use App\Models\Identifiedobject;
use App\Models\Connector;

// контроллер модели
class EndpointController extends Controller
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
        $content = Endpoint::with('connector', 'identifiedobject', 'acline')->paginate($paginate);

        // открыть вюшку
        return view('backend.endpoint.index', compact('content'));
    }

    // вывод одной строки
    public function edit(Request $request, $id = null)
    {
        // проверить параметры, возможно переданные через post запрос
        $thisModal = $request->input('thisModal');

        // контент
        if ($id) {
            $content = Endpoint::findOrFail($id);
        } else {
            $content = new Endpoint;
        }

        // справочники и другие дополнительные сведения
        $identifiedobjects = Identifiedobject::all();
        $connectors = Connector::all();

        // возвращаемый параметр
        if (is_null($thisModal)) {
            // открыть вьюшку
            return view('backend.endpoint.edit', compact('content', 'identifiedobjects', 'connectors'));
        } else {
            // вернуть готовый рендер страницы
            $html = view('backend.endpoint.editcontent')->with(
                [
                    'thisModal' => $thisModal,
                    'content' => $content,
                    'identifiedobjects' => $identifiedobjects,
                    'connectors' => $connectors,
                ])->render();
            return response()->json(['html' => $html, 'code' => 200]);
        }
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        // проверить параметр модального окна
        $thisModal = $request->input('thisModal');

        $model = $this->commonCrudService->store('Endpoint', $id, $request);

        if (is_null($thisModal)) {
            // редирект
            return redirect(route('endpoint.index'));
        } else {
            // далее закрытие модального окна
            $id = $model->id;
            $model = Endpoint::find($id); // почемму то $model не может получить свое имя из IO
            $myName = $model->identifiedobject->name;
            return response()->json(['id' => $id, 'name' => $myName]);
        }
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Endpoint', $id);
        // редирект
        return redirect()->back();
    }

    // получение рендера вьюшки связанных точек
    public function ajaxLoadRelatedEndpoint(Request $request)
    {
        // параметры, переданные через post запрос
        $connector_id = $request->input('connector_id');

        // данные фидера
        $connector = Connector::find($connector_id);
        $sheme_thumb = '/public/' . $connector->getImage('thumb', 'img');
        $sheme_hd = '/public/' . $connector->getImage('hd', 'img');

        // вернуть готовый рендер
        $html = view('backend.blocks_edit.related_endpoint')->with(
            [
                'myRelatedEndpoint' => $connector->endpoint,
                'sheme_thumb' => $sheme_thumb,
                'sheme_hd' => $sheme_hd,
            ])->render();
        return response()->json(['html' => $html, 'code' => 200]);
    }
}