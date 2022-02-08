<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// мои сервисы
use App\Http\Services\backend\ModelService;
use App\Http\Services\backend\FirebirdService;

// контроллер импорта таблиц
class TableController extends Controller
{
    // подключение сервисов
    public function __construct(ModelService $modelService, FirebirdService $firebirdService)
    {
        $this->modelService = $modelService;
        $this->firebirdService = $firebirdService;
    }

    // вывод таблиц с одинаковым названием для импорта
    public function forImports(Request $request)
    {
        // список всех таблиц в бд Firebird
        $tablesFB = $this->firebirdService->tables();
        // получение списка всех таблиц и их полей
        $tablesFBwithFields = $this->firebirdService->tablesWithFields($tablesFB, 0, 1);

        // список всех моделей
        $models = $this->modelService->models();
        // список таблиц, которые используют модели и их поля
        $tablesMySQLwithFields = $this->modelService->tablesWithFields($models, 0, 1);

        // подготовить список, в котором напротив каждой таблицы MySQ: будет с таким же именем таблица Firebird
        $content = [];
        foreach ($tablesMySQLwithFields as $tableMySQLwithFields) {
            foreach ($tablesFBwithFields as $tableFBwithFields) {
                if ($tableMySQLwithFields['table'] == $tableFBwithFields['table']) {
                    $content [] = ['mysql' => $tableMySQLwithFields, 'firebird' => $tableFBwithFields];
                }
            }
        }

        //открыть вьшку
        return view('backend.table.import', compact('content'));
    }

    // просмотр всех таблиц Firebird
    public function viewTablesFirebird()
    {
        // список всех таблиц в бд Firebird
        $tablesFB = $this->firebirdService->tables();
        // получение списка всех таблиц и их полей
        $content = $this->firebirdService->tablesWithFields($tablesFB, 0, 1);
        // тип таблиц
        $typeTsbles = 'Firebird';

        //открыть вьшку
        return view('backend.table.many', compact('content', 'typeTsbles'));
    }

    // просмотр всех таблиц MySQL
    public function viewTablesMySQL()
    {
        // список всех моделей
        $models = $this->modelService->models();
        // список таблиц, которые используют модели и их поля
        $content = $this->modelService->tablesWithFields($models, 0, 1);
        // тип таблиц
        $typeTsbles = 'MySQL';

        //открыть вьшку
        return view('backend.table.many', compact('content', 'typeTsbles'));
    }

    // просмотр одной таблицы Firebird
    public function viewTableFirebird($table)
    {
        // получить список полей таблицы Firebird
        $fields = $this->firebirdService->fields($table);

        // получить данные из Firebird
        $content = [];
        if (count($fields) > 0) {
            // список полей сформирован и он не пусто
            // получить данные из Firebird
            $content = $this->firebirdService->zaprosData($table, $fields);
        }

        // тип таблиц
        $typeTsbles = 'Firebird';

        // открыть вюшку
        return view('backend.table.one', compact('content', 'fields', 'table', 'typeTsbles'));
    }

    // просмотр одной таблицы MySQL
    public function viewTableMySQL($model)
    {
        // создание экземпляра модели по ее имени
        $model = $this->modelService->makeModel($model);

        // получение данных модели
        $content = $model::all();

        // получение списка полей модели
        $fields = $this->modelService->fields($model);

        // получение названия таблицы модели
        $table = $model->getTable();

        // тип таблиц
        $typeTsbles = 'MySQL';

        // открыть вюшку
        return view('backend.table.one', compact('content', 'fields', 'table', 'typeTsbles'));
    }
}

