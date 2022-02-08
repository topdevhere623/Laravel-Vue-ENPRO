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
use App\Models\File;
use App\Models\Picturetype;

// контроллер модели
class FileController extends Controller
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
        $content = File::paginate($paginate);

        // открыть вюшку
        return view('backend.file.index', compact('content'));
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = File::findOrFail($id);
        } else {
            $content = new File;
        }

        // справочники и другие дополнительные сведения
        $pictureTypes = Picturetype::all();

        // открыть вьюшку
        return view('backend.file.edit', compact('content', 'pictureTypes'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $bool = $this->commonCrudService->store('File', $id, $request, 'src'); // потому что у этой модеди поле не img, а src
        // редирект
        return redirect(route('file.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('File', $id, 'src'); // потому что у этой модеди поле не img, а src
        // редирект
        return redirect()->back();
    }
}