<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// мои сервисы
use App\Http\Services\backend\CommonFileService;
use App\Http\Services\backend\CommonCrudService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Http\Services\API\TaskService;
use App\Http\Services\API\TaskParseService;

// модель
use App\Models\Task;
use App\Models\Tasktype;
use App\Models\User;
use App\Models\Substation;
use App\Models\Connector;
use App\Models\File;

// контроллер модели
class TaskController extends Controller
{
    // подключение сервисов
    public function __construct(CommonFileService $commonFileService, CommonCrudService $commonCrudService, CommonService $commonService, ModelService $modelService, TaskService $taskService, TaskParseService $taskParseService)
    {
        $this->commonFileService = $commonFileService;
        $this->commonCrudService = $commonCrudService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
        $this->taskService = $taskService;
        $this->taskParseService = $taskParseService;
    }

    // вывод списка
    public function index()
    {
        // пагинация
        $paginate = $this->commonService->getAdmminSetting('setting_paginate_admin');
        // получение данных модели
        $content = Task::with('tasktype', 'user', 'substation', 'connector', 'file')->latest()->paginate($paginate);

        // сканирование этого списка, чтобы проставить кол-во имеющихся json-файлов в папке модели
        foreach ($content as $item) {
            // получение списка всех имеющихся json-файлов в папке конкретной модели task
            $return = $this->taskService->jsonFiles($item->id);
            // добавить поле с кол-вом json-файлов
            $item->setAttribute('jsonFilesKol', count($return));
        }

        // открыть вюшку
        return view('backend.task.index', compact('content'));
    }

    // вывод одной строки
    public function edit($id = null)
    {
        // контент
        if ($id) {
            $content = Task::findOrFail($id);

            // режим редактирования
            // парсинг файла, если был получен из мобильного придожения
            if ($content->json_file <> '') {
                // парсинг json-файла (оставил только при получении файла через API. Здесь только для отладки)
                $return = $this->taskParseService->parseJsonFile($content->folderPath() . '/' . $content->json_file);
                if ($return['code'] == 200) {
                    // парсинг прошел успешно

                    // записать полученные дополнительные данные припарсинге в переменные
                    $content->json_parse_file = $return['object'];
                    $content->json_parse_text = $return['comment'];

                    // относительный путь модели + имени json-а без расширения
                    $dir = $content->folderPath() . '/' . pathinfo($content->json_file, PATHINFO_FILENAME);

                    // получить изображения в папке json
                    $arrJsonImg =
                        [
                            'thumb' => $this->commonFileService->getImgFromDir($dir, 'jpg', '_thumb.'),
                            'dir' => '/public/' . $dir . '/',
                        ];
                } else {
                    // парсинг прошел с ошибкой
                    // записать в переменную
                    $content->json_parse_text = $return['message'];
                }
            }
        } else {
            $content = new Task;
        }

        // начальные пустые значения
        if (!isset($content->json_parse_file)) $content->json_parse_file = '';
        if (!isset($content->json_parse_text)) $content->json_parse_text = '';
        if (!isset($arrJsonImg)) $arrJsonImg = null;

        // справочники и другие дополнительные сведения
        $tasktypes = Tasktype::all();
        $users = User::all();
        $substations = Substation::all();
        $connectors = Connector::all();
        $files = File::all();

        // открыть вьюшку
        return view('backend.task.edit', compact(
            'content', 'tasktypes', 'substations', 'users', 'connectors', 'files',
            'arrJsonImg'
        ));
    }

    // сохранение данных
    public function update($id = null, Request $request)
    {
        $bool = $this->commonCrudService->store('Task', $id, $request, '', 'save_pivot');
        // редирект
        return redirect(route('task.index'));
    }

    // удаление строки
    public function destroy($id)
    {
        // удаление изображений и в бд
        $bool = $this->commonCrudService->destroy('Task', $id);
        // редирект
        return redirect()->back();
    }

    // получение списка всех имеющихся json-файлов в папке модели task
    public function jsonFiles($id = null)
    {
        // получение списка всех имеющихся json-файлов в папке модели task
        $content = $this->taskService->jsonFiles($id);

        // открыть вьюшку
        return view('backend.task.jsonFiles', compact('content'));
    }

    // парсинг json-файла задачи
    public function jsonParse()
    {
        // полученные переменные через POST
        $fileName = $_REQUEST['fileName'];
        // распарсить файл
        $return = $this->taskParseService->parseJsonFile($fileName);

        // возвращаемый параметр
        return response()->json(['html' => $return['comment'], 'code' => $return['code']]);
    }

    // удаление json-файла, его папки после парсинга со всеми изображениями и новым легким json-ом
    public function jsonDelete()
    {
        // полученные переменные через POST
        $fileName = $_REQUEST['fileName'];

        // удалить все
        $this->taskService->jsonDelete($fileName);
    }
}