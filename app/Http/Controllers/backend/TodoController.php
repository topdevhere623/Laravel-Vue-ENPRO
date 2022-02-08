<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;

// модель
use App\Models\Todo;
use App\Models\TodoStageFioPivot;
use App\Models\File;

// контроллер модели
class TodoController extends Controller
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
        $content = Todo::paginate($paginate);

        // открыть вюшку
        return view('backend.todo.index', compact('content'));
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Todo::with('todostagefiopivot')->findOrFail($id);
            //$content->files = json_decode($content->files, true);
        } else {
            $content = new Todo;
        }

        // открыть вьюшку
        return view('backend.todo.edit', compact('content'));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        // список всех полей
        $requestAll = $request->all();

        // функция прелобразования поля дататайм
        $request->merge(['date_begin' => $this->commonService->convertDateTimeTo_HHMM_DDMMYYYY($request['date_begin'])]);
        $request->merge(['date_end' => $this->commonService->convertDateTimeTo_HHMM_DDMMYYYY($request['date_end'])]);

        // сохранение основных данных
        if ($id) {
            $model = Todo::findOrFail($id);
        } else {
            $model = new Todo;
        }
        $model->fill([
            'name' => $request['name'],
            'description' => $request['description'],
            'date_begin' => $request['date_begin'],
            'date_end' => $request['date_end'],
            'status_id' => $request['status_id'],
        ])->update();

        // существующий или новый id родителя
        $todo_id = $model->id;

        // сохранение изображений
//        $files_old = json_decode($model->files, true);
//        if (is_null($files_old)) $files_old = [];
//        $files = [];
//        foreach ($requestAll as $key => $item) {
//            if (Str::contains($key, 'img_')) {
//                $index = str_replace('img_', '', $key);
//                if (array_search($request['img-current_' . $index], $files_old) == false) {
//                    // удаление старого изображения с диска
//                    $this->commonFileService->serviceDeleteOldImage($model, $request['img-current_' . $index]);
//                    // обновляем изображения (id в любом случае уже известно)
//                    $files [] = [
//                        'name' => $this->commonFileService->serviceUploadedImage($request['img' . $index], $model),
//                        'title' => $request['file_title_' . $index]
//                    ];
//                }
//            }
//        }
//        $model->fill([
//            'files' => json_encode($files),
//        ])->update();

        // сохранение таблицы pivot
        // сперва удалить все старые записи
        TodoStageFioPivot::where('todo_id', $todo_id)->delete();
        foreach ($requestAll as $key => $item) {
            if (Str::contains($key, 'stage_id_')) {
                $index = str_replace('stage_id_', '', $key);
                $model = new TodoStageFioPivot();
                $model->fill([
                    'todo_id' => $todo_id,
                    'stage_id' => $request['stage_id_' . $index],
                    'fio_id' => $request['fio_id_' . $index],
                ])->save();
            }
        }

        // редирект
        return redirect(route('todo.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Todo', $id);
        // редирект
        return redirect()->back();
    }
}
