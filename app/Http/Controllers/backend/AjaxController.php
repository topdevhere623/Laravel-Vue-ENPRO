<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\ModelService;
use App\Http\Services\API\getFileService;
use App\Http\Services\API\TaskService;

// модели
use App\Models\Task;

// контроллер запросов по Ajax
class AjaxController extends Controller
{
    // подключение сервисов
    public function __construct(ModelService $modelService, getFileService $getFileService, TaskService $taskService)
    {
        $this->modelService = $modelService;
        $this->getFileService = $getFileService;
        $this->taskService = $taskService;
    }

    // обновление файла json (для задачи, первоисточник, обновление координат после измененния на карте)
    public function ajaxUpdateTaskJsonMap(Request $request)
    {
        // полученные переменные через AJAX
        $getTaskId = $request->input('task_id');
        $getMyArrPoints = $request->input('myArrPoints');

        $return = $this->taskService->updateTaskJsonMap($getTaskId, $getMyArrPoints);

        // возвращаемый параметр
        return $return;
    }

    // импорт таблицы
    public function ajaxImportTable(Request $request)
    {
        // полученные переменные через AJAX
        $model = $request->input('model');

        // импорт из Firebird в модель MySQL
        $return = $this->modelService->import($model);

        // возвращаемый параметр
        return json_encode($return);
    }

    // очистка таблицы модели
    public function ajaxClearTable(Request $request)
    {
        // полученные переменные через AJAX
        $table = $request->input('table');

        DB::beginTransaction();
        try {

            // очитска таблицы
            DB::table($table)->truncate();

            // попытка сделать транзакцию
            DB::commit();
        } catch (Exception $ex) {
            // транзакция не прошла - отменить все действия
            DB::rollback();
            echo $ex->getMessage();
        }

        // возвращаемый параметр
        return "Готово";
    }

    // смена значения поля (статуса, новинка, хит)
    public function ajaxChangeField()
    {
        // полученные переменные
        $id = $_REQUEST['id'];
        $model = $_REQUEST['model'];
        $field = $_REQUEST['field'];

        // узнать текущий состояние
        $content = ("\App\Models\\" . $model)::find($id);
        $myCurrent = ($content->$field == 1 ? 0 : 1);
        $content->$field = $myCurrent;
        // сохранить новое значение
        $content->update();

        // чтобы увидеть в консоле
        return 'OK';
    }

    // смена значения поля (статуса, новинка, хит)
    public function ajaxClearCache()
    {
        // очистка кеша
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return "Кэш очищен.";
    }

    // отладка API запросов
    public function ajaxApiTest(Request $request)
    {
        // полученные переменные
        // запрос в url - оно же имя контроллера
        $api = $_REQUEST['api'];

        $return = 'Ничего не получено!';

        // вызвать нужный контроллер
        //$controllerName = ("App\Http\Controllers\API\FileController@") . $api; // не вызывается так
        //$return = app()->call($controllerName);

        switch ($api) {

            case "parseJsonFile":
                $return = app()->call('App\Http\Services\API\TaskParseService@parseJsonFile', ['getJsonFileName' => 'uploads/models/task/1/20200720_015847.json']);
                break;

            case "getTasks":
                $return = app()->call('App\Http\Controllers\API\TaskController@getTasks', []);
                break;

            case "getTask":
                $return = app()->call('App\Http\Controllers\API\TaskController@getTask', []);
                break;
            case "changeTaskStatus":
                $return = app()->call('App\Http\Controllers\API\TaskController@changeTaskStatus', []);
                break;
            case "changeTaskStart":
                $return = app()->call('App\Http\Controllers\API\TaskController@changeTaskStart', []);
                break;
            case "changeTaskEnd":
                $return = app()->call('App\Http\Controllers\API\TaskController@changeTaskEnd', []);
                break;

            case "getPoint":
                $return = $this->taskService->getPoint($request);
                break;
            case "getLineSegment":
                $return = $this->taskService->getLineSegment($request);
                break;
            case "getNewPoint":
                $return = $this->taskService->getNewPoint($request);
                break;
            case "getTower":
                $return = $this->taskService->getTower($request);
                break;
            case "getTowerMounting":
                $return = $this->taskService->getTowerMounting($request);
                break;
            case "getPotrebitelPoint":
                $return = $this->taskService->getPotrebitelPoint($request);
                break;

            case "getFile":
                // прочитать и подготовить указанный файл для отправки (упакованное содержимое, mime, хеш)
                $return = $this->getFileService->getFile('/uploads/models/connector/1/01.jpg');
                break;

            case "getTable":
                $return = app()->call('App\Http\Controllers\API\TablController@getTable', []);
                break;
        }

        // возвращаеимый параметр
        // ответ json
        $response = json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // чтобы красиво в форматированном виде было + русские буквы
        // заголовок
        $response = response()->json($response, 200, ['Content-Type: application/json']);
        return $response;
    }
}