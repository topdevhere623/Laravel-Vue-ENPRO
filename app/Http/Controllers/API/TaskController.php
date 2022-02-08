<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\API\saveFileService;
use App\Http\Services\API\getFileService;
use App\Http\Services\backend\CommonService;
use App\Http\Services\backend\ModelService;
use App\Http\Services\API\TaskService;
use App\Http\Services\API\TaskParseService;

// модель
use App\Models\Task;
use App\Models\User;

// контроллер Task (получение/сохранение)
class TaskController extends Controller
{
    // подключение сервисов
    public function __construct
    (
        saveFileService $saveFileService,
        getFileService $getFileService,
        CommonService $commonService,
        ModelService $modelService,
        TaskService $taskService,
        TaskParseService $taskParseService
    )
    {
        $this->saveFileService = $saveFileService;
        $this->getFileService = $getFileService;
        $this->commonService = $commonService;
        $this->modelService = $modelService;
        $this->taskService = $taskService;
        $this->taskParseService = $taskParseService;
    }

    // получить список задач Tasks (со списком всех подстанций и коннекторов)
    // POST: getTasks?user=2&type=1
    public function getTasks(Request $request)
    {
        // проверка на получение параметров через post запрос
        if (!isset($request['user'])) return response()->json("Ошибка! Не получен обязательный параметр [user]", 400);
        if (!isset($request['type'])) return response()->json("Ошибка! Не получен обязательный параметр [type]", 400);

        // полученные параметры
        $getUser = $request['user'];
        $getTaskType = $request['type'];

        // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
        if ($this->modelService->hasIdInModel('User', $getUser) == false) return response()->json("Ошибка! Пользователь [" . $getUser . "] не обнаружен!", 404);
        if ($this->modelService->hasIdInModel('Tasktype', $getTaskType) == false) return response()->json("Ошибка! Тип задачи [" . $getTaskType . "] не обнаружен!", 404);

        // запрос в базу данных
        $tasks = Task::with('tasktype', 'user')->where(['user_id' => $getUser, 'tasktype_id' => $getTaskType])->get();
        if (count($tasks) == 0) return response()->json("Ошибка! По заданным параметрм задач не обнаружено!", 404);

        // сканировать список задач
        $return = [];
        foreach ($tasks as $task) {
            // данные одной задачи из БД
            $return [] = self::getTaskOne($task->id);
        }

        // возвращаемый параметр
        return response()->json($return, 200);
    }

    // данные одной задачи из БД
    public function getTaskOne($taskId)
    {
        // запрос в базу данных
        $task = Task::with('substation', 'connector')->where('id', $taskId)->get()->first();

        // создать массив конечных точек для текущей задачи
        $endpoints = [];
        if (isset($task->connector->endpoint) and count($task->connector->endpoint) > 0) {
            foreach ($task->connector->endpoint as $item) {
                $endpoints [] =
                    [
                        'id' => $item->id,
                        'status' => $item->status,
                        'localname' => $item->identifiedobject->name,
                    ];
            }
        }

        // схемы фидеров
        $file = $task->connector->getImage('hd', 'img'); // если схемы-фидера нет, то поставить заглушку, чтоб на Клиенте не было ошибки (а то не загружает список там)
        // прочитать и подготовить указанный файл для отправки (упакованное содержимое, mime, хеш)
        $return = $this->getFileService->getFile($file);
        $fiderSheme = $return["fileContent"];

        $return =
            [
                'task' => $task->uuid,
                'substations' =>
                    [
                        'id' => $task->substation->id,
                        'name' => $task->substation->identifiedobject->name,
                        'address' => $task->substation->identifiedobject->address,
                    ],
                'connectors' =>
                    [
                        'id' => $task->connector->id,
                        'name' => $task->connector->identifiedobject->name,
                        'localname' => $task->connector->identifiedobject->localname,
                        'voltage_id' => $task->connector->identifiedobject->voltage_id,
                        'endpoints' => $endpoints,
                        'image' => $fiderSheme,
                        'status' => 'готово',
                    ],
            ];

        return $return;
    }

    // **********************************************************************************
    // получить данные по одной задаче
    // POST: getTask?task=1&user=2
    public function getTask(Request $request)
    {
        // проверка на получение параметров через post запрос
        if (!isset($request['task'])) return response()->json("Ошибка! Не получен обязательный параметр [task]", 400);
        if (!isset($request['user'])) return response()->json("Ошибка! Не получен обязательный параметр [user]", 400);

        // полученные параметры
        $getTask = $request['task'];
        $getUser = $request['user'];

        // временно!!!! режим работы. Отправлять полученный json, который пришел или нарабатывать свой, как было изначально
        $regim = 'new';
        if (isset($request['regim'])) {
            $regim = 'new';
        }
        if ($regim == 'new') {
            // отправка данных по-новому
            $Task = self::getTaskNew($getTask);
            // возвращаемый параметр
            return response()->json($Task, 200);
        }

        // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
        if ($this->modelService->hasIdInModel('Task', $getTask, 'uuid') == false) return response()->json("Ошибка! Задача [" . $getTask . "] не обнаружена!", 404);
        if ($this->modelService->hasIdInModel('User', $getUser) == false) return response()->json("Ошибка! Пользователь [" . $getUser . "] не обнаружен!", 404);

        // запрос в базу данных
        $task = Task::find($getTask);

        // открыть и декодировать полученный json
        if ($task->json_file <> '') {
            // файл json есть
            // открыть json-файл по id-задачи (исходник или новый "легкий") (оригинал, потому что там есть фото)
            $return = $this->taskService->jsonLoadTask($task->id, 'original');
            $jsonFile = $return['jsonFile'];
            $jsonFile = $jsonFile['connectors'];
            // обьекты
            $jsonStartPoint = $jsonFile['startPoint'];
            $jsonPoints = $jsonFile['points'];
            //$jsonTowers = $jsonFile['tower'];
        } else {
            // файла json еще не было
            // обьекты
            $jsonStartPoint = null;
            $jsonPoints = null;
        }

        // получить обьект Point
        $point = $this->taskService->getPoint($jsonStartPoint);
        // получить обьект LineSegment (линия, шаг 1 из 6)
        $lineSegment = $this->taskService->getLineSegment($request);
        // получить обьект newPoint (новая точка, шаг 2 из 6)
        $newPoint = $this->taskService->getNewPoint($jsonPoints);
        // получить обьект Tower (опора, шаг 3 из 6)
        $tower = $this->taskService->getTower($request);
        // получить обьект TowerMounting (подвес опоры, ша 4 из 6)
        $towerMounting = $this->taskService->getTowerMounting($request);
        // получить обьект PotrebitelPoint
        $potrebitelPoint = $this->taskService->getPotrebitelPoint($request);

        // задача
        $Task = [
            'point' => $point,
            'lineSegment' => $lineSegment,
            'newPoint' => $newPoint,
            'tower' => $tower,
            'towerMounting' => $towerMounting,
            'potrebitelPoint' => $potrebitelPoint,
            //'fiderSheme' => $fiderSheme, // Михаил сказал, что схему-фидера в спсике задач лучше ему получать
        ];

        // возвращаемый параметр
        return response()->json($Task, 200);
    }

    // получить данные по одной задаче по-новому (на основе присланного json-а)
    public function getTaskNew($getTask)
    {
        // запрос в базу данных
        $task = Task::where('uuid', $getTask)->get()->first();
        $jsonFileName = $task->folderPath() . '/' . $task->json_file;

        // проверить, еть ли уже файл json
        if ($task->json_file <> '' and file_exists(public_path() . '/' . $jsonFileName)) {
            // да, есть
            // отправить его как есть (он зашифрован base64, есть task, substation, connection)

            // открыть и декодировать json-файл по его имени
            $return = $this->taskService->loadJson($jsonFileName);
            $return = $return['jsonFile'];

        } else {
            // нет, еще нету
            // отправить только task, substation, connection

            // данные одной задачи из БД
            $return = self::getTaskOne($task->id);
        }

        // возвращаемый параметр
        return $return;
    }

    // сохранить полученный файл задачи Task
    // POST: saveTask
    public function saveTask(Request $request)
    {
        // для отладки!!! - сохранение всех полученных данных в тектсовый файл
        if (false) {
            $fileSave = public_path() . '/uploads/mmmmyyyyyy.log';
            //$globals = print_r($request, true); // $GLOBALS // пишем ассоциативный массив в переменную
            $globals = var_export($request, true);
            $file = fopen($fileSave, "w+"); // открываем файл
            fwrite($file, $globals); // пишем в него
            fclose($file); // закрываем
            return response()->json('Сохранил request', 200);
        }

        // проверка на получение параметров через post запрос
        if (true) {
            if (!isset($request['task'])) return response()->json("Ошибка! Не получен обязательный параметр [task]", 400);
            if (!isset($request['user'])) return response()->json("Ошибка! Не получен обязательный параметр [user]", 400);
            if (!isset($request['hash'])) return response()->json("Ошибка! Не получен обязательный параметр [hash]", 400);
            if (!isset($request['file'])) return response()->json("Ошибка! Не получен обязательный параметр [file]", 400);
        }

        // полученные параметры
        $getTask = $request['task'];
        $getUser = $request['user'];
        $getHash = $request['hash'];
        $getFileData = $_FILES['file']; // массив данных полученного файла (имя, тип, размер, временное имя, ошибки)

        // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
        if (false) {
            if ($this->modelService->hasIdInModel('Task', $getTask) == false) return response()->json("Ошибка! Задача [" . $getTask . "] не обнаружена!", 404);
            if ($this->modelService->hasIdInModel('User', $getUser) == false) return response()->json("Ошибка! Пользователь [" . $getUser . "] не обнаружен!", 404);
        }

        // попробовать найти полученную задачу на сервере по uuid
        $task = Task::where('uuid', $getTask)->get()->first();
        if (is_null($task)) {
            // нет, такой задачи еще не было - создать новую строчку
            $task = new Task;
            $task->uuid = $getTask;
            $task->save();
        }
        // id задачи
        $taskId = $task->id;

        // сохранить полученный файл (ему будет будет присвоено новое имя, например 20200722_025801.json )
        // у одной задачи - один пользователь - только один файл
        $fileDir = "/uploads/models/task/" . $taskId;
        $fileSave = date('Ymd_his') . ".json";
        $returnSaveFile = $this->saveFileService->saveFile($fileDir, $fileSave, $getHash, $getFileData);

        // проверка, успешно ли сохранился принятый файл
        if ($returnSaveFile['code'] == 200) {
            // файл был успешно сохранен

            // получить имя задачи из файла
            $returnLoadJson = $this->taskService->loadJson($fileDir . '/' . $fileSave);
            $json = $returnLoadJson['jsonFile'];
            $taskName = isset($json) ? $json['name'] : 'Имя задачи не определено';

            // записать в моделе Task
            $task->title = $taskName;
            $task->json_file = $fileSave;
            $task->user_id = $getUser;
            $task->tasktype_id = 1; // !!!!!!!!!!!!!!!! в будующем это с Клиента должно приходить
            $task->update();

            // распарсить полученный файл (картинки сохранить на диске)
            //$bool = $this->taskService->parseJsonFile($fileSave);
        }

        // возвращаемый параметр
        return response()->json($returnSaveFile['message'], $returnSaveFile['code']);
    }

    // **********************************************************************************
    // изменить статус задачи
    // POST: changeTaskStatus?task=1&status=10
    public function changeTaskStatus(Request $request)
    {
        // проверка на получение параметров через post запрос
        if (!isset($request['task'])) return response()->json("Ошибка! Не получен обязательный параметр [task]", 400);
        if (!isset($request['status'])) return response()->json("Ошибка! Не получен обязательный параметр [status]", 400);

        // полученные параметры
        $getTask = $request['task'];
        $getStatus = $request['status'];

        // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
        if ($this->modelService->hasIdInModel('Task', $getTask) == false) return response()->json("Ошибка! Задача [" . $getTask . "] не обнаружена!", 404);

        // записать новое значение статуса
        $task = Task::find($getTask);
        $task->status = $getStatus;
        $task->update();

        // возвращаемый параметр
        return response()->json("Статус задачи [" . $getTask . "] успешно обновлен на [" . $getStatus . "]", 200);
    }

    // **********************************************************************************
    // задачу начать (записать поле startdate)
    // POST: changeTaskStart?task=2
    public function changeTaskStart(Request $request)
    {
        // записать дату в поле (вспомогтальеная)
        return self::changeTaskDate($request, 'startdate');
    }

    // задачу закончить (записать поле enddate)
    // POST: changeTaskEnd?task=3
    public function changeTaskEnd(Request $request)
    {
        /// записать дату в поле (вспомогтальеная)
        return self::changeTaskDate($request, 'enddate');
    }

    // записать дату в поле (вспомогтальеная)
    public function changeTaskDate($request, $field)
    {
        // проверка на получение параметров через post запрос
        if (!isset($request['task'])) return response()->json("Ошибка! Не получен обязательный параметр [task]", 400);

        // полученные параметры
        $getTask = $request['task'];

        // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
        if ($this->modelService->hasIdInModel('Task', $getTask) == false) return response()->json("Ошибка! Задача [" . $getTask . "] не обнаружена!", 404);

        // записать новое значение статуса
        $task = Task::find($getTask);
        $now = $this->commonService->getDateTime();
        $task->$field = $now;
        $task->update();

        switch ($field) {
            case 'startdate':
                $message = "начала";
                break;
            case 'enddate':
                $message = "завершения";
                break;
        }

        // возвращаемый параметр
        return response()->json("Время " . $message . " задачи [" . $getTask . "] успешно обновлено на [" . $now . "]", 200);
    }
}

