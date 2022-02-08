<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// мои сервисы
use App\Http\Services\backend\ModelService;

// модель

// контроллер получения таблицы через запрос API
class TablController extends Controller
{
    // подключение сервисов
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    // получить данные таблицы по ее имени и id строки (если имя таблицы не указано, то выдать список всех имеющихся таблиц)
    public function getTable(Request $request)
    {
        // параметры, полученные через строку url
        $getTable = $request->input('table');
        $getId = $request->input('id');
        $getOnlyWithComment = $request->input('onlywithcomment');
        // начальное значение
        $return = [];

        // проверка, указали ли имя таблицы
        if (is_null($getTable)) {
            // имя таблицы не указано - выдать список всех моделей
            $tables = $this->modelService->models();
            $content = [];
            // сканировать этот список и искать комментарий из одноименной модели
            foreach ($tables as $item) {
                // имя таблицы
                $comment = $this->modelService->nameTabl($item);
                if ($comment == '') {
                    if (!is_null($getOnlyWithComment) and $getOnlyWithComment == 'yes') {
                        // в спсиок для вывода включать только с комментарием - пустые пропускать
                    } else {
                        // включать все
                        $content [] = $item;
                    }
                } else {
                    $content [] = $item . ' - ' . $comment;
                }
            }
            $return [] = 'Список всех таблиц, имеющихся в базе данных:';
            $return [] = $content;
        } else {
            // имя таблицы указано
            // имя класса
            $myClass = "\App\\Models\\" . ucfirst($getTable);

            // проверка, передано ли id
            if (is_null($getId)) {
                // id не указано - выдать все строчки
                // запрос в БД
                $content = $myClass::all();
                //$return [] = 'Таблица: ' . $getTable;
                foreach ($content as $item) {
                    $return [] = $item;
                }
            } else {
                // id указано
                // запрос в БД
                $content = $myClass::find($getId);
                $return [] = 'Таблица: ' . $getTable . ', id: ' . $getId;
                $return [] = $content;
            }
        }

        // возвращаемый параметр
        return response()->json($return, 200);
    }
}