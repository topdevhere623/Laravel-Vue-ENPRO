<?php

namespace App\Http\Services\backend;

use Doctrine\DBAL\Types\BigIntType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// мои сервисы
use App\Http\Services\backend\FirebirdService;

// сервис моделей
class ModelService
{
    // подключение сервисов
    public function __construct(FirebirdService $firebirdService)
    {
        $this->firebirdService = $firebirdService;
    }

    // имя папки, где хранится список моделей
    public function getModelsPath()
    {
        return app_path() . "/Models";
    }

    // проверка, есть ли указанное id в модели - вернет "true" - если запись есть
    public function hasIdInModel(String $model, $id, String $field = 'id')
    {
        // создание экземпляра модели по ее имени
        $model = self::makeModel($model);

        // запрос в базу данных
        $query = $model::where($field, $id)->get()->first();

        // возвращаемый параметр
        return !is_null($query);
    }

    // список моделей
    public function models()
    {
        // имя папки, где хранится список моделей
        $path = self::getModelsPath();
        // рекурсивная функция поиска имен моделей
        $models = self::getModels($path);
        // убрать путь с диском, оставить только полное имя моделей
        $return = [];
        foreach ($models as $item) {
            // убрать путь с диском
            $modelName = str_replace($path . '/', '', $item);
            // полное имя модели
            $modelName = ucfirst($modelName);
            // записать в массив
            $return[] = $modelName;
        }

        // возвращаемый параметр
        return $return;
    }

    // рекурсивная (отключил рекурсию!!! из-за папки no) функция поиска имен моделей
    // вернет массив со строками: "D:\DOMAINS\firebird6.loc\app/Models/Accountinterpriseserv"
    public function getModels($path)
    {
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $filename = $path . '/' . $result;
            if (is_dir($filename)) {
                //$out = array_merge($out, getModels($filename));
            } else {
                $out[] = substr($filename, 0, -4);
            }
        }
        return $out;
    }

    // список таблиц, которые используют модели и их поля + имена само имя модели (на тот случай, если имя модели отличаеться от имени таблицы)
    public function tablesWithFields($models, $tabls_maska, $need_fields)
    {
        $return = [];
        foreach ($models as $item) {
            // полное имя модели
            $modelName = "\App\\Models\\" . $item;
            // модель
            $model = new $modelName;
            // имя модели
            $modelName = get_class($model);
            // имя таблицы
            $table = trim(Str::upper($model->getTable()));
            // проверить, нужно ли ограничить по маске в имени
            if ($tabls_maska == 1) {
                if (!Str::contains($table, 'KIND') and !Str::contains($table, 'INFO')) continue;
            }

            $fields = [];
            if ($need_fields == 1) {
                // поля нужны
                // список полей для модели
                $fields = self::fields($model);
            }
            // кол-во строк в таблице
            $count = self::count($model);
            // комментарий к таблице
            $comment = $model::title2;

            // записать в массив
            $return[] = ['table' => $table, 'fields' => $fields, 'model' => $item, 'count' => $count, 'comment' => $comment];
        }
        // возвращаемый параметр
        return $return;
    }

    // список полей для модели
    public function fields($model)
    {
        // получение списка полей модели
        $fields = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        // возвращаемый параметр
        return $fields;
    }

    // создание экземпляра модели по ее имени
    public function makeModel($getModelName)
    {
        // проверка, что именно наззвание модели передали, а не ее экземпляр
        if (gettype($getModelName) == 'string') {
            // полное имя модели
            $model = "App\\" . (Str::contains($getModelName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($getModelName);
            // создать экземпляр
            $model = new $model;
        }
        // возвращаемый параметр
        return $model;
    }

    // кол-во строк в таблице
    public function count($model)
    {
        $count = $model::count();
        // возвращаемый параметр
        return $count;
    }

    // название таблицы из поля модели
    public function nameTabl($tabl)
    {
        // модель
        $model = self::makeModel($tabl);
        // попробовтаь прочитать поле модели, если есть
        try {
            // комментарий у модели
            $nameTabl = $model::title2;

        } catch (\Exception $e) {
            $nameTabl = '';
            //continue;
        }

        // возвращаемый параметр
        return $nameTabl;
    }

    // импорт из Firebird в модель MySQL
    public function import($model)
    {
        // создание экземпляра модели по ее имени
        $model = self::makeModel($model);

        // получение названия таблицы модели
        $table = Str::upper($model->getTable());

        // получить список полей таблицы Firebird
        $fieldsFB = $this->firebirdService->fields($table);

        // получение списка полей модели
        $fields = self::fields($model);
        $fieldsMySQL = [];
        $fieldsMySQLImport = [];
        foreach ($fields as $item) {
            $item = $item;
            // все поля
            $fieldsMySQL [] = trim($item);
            // поля для импорта ("лишние" исключаю, и только те, которые есть в FB)
            if ($item <> 'deleted_at' and $item <> 'created_at' and $item <> 'updated_at' and in_array(Str::upper($item), $fieldsFB)) $fieldsMySQLImport [] = $item;
        }

        // получить данные из Firebird
        $dataFB = [];
        if (count($fieldsMySQLImport) > 0) {
            // список полей сформирован и он не пусто
            // получить данные из Firebird
            $dataFB = $this->firebirdService->zaprosData($table, $fieldsMySQLImport);
        }

        // очистить таблицу MySQL (чтобы не было повторов и ошибки с дубликатом id)
        //$request->request->add(['table' => $table]);
        //self::ajaxClearTable($request);

        // сохранить в таблице MySQL
        //$model::insert($dataFB); // ошибку так дает
        // начальные значения
        $hasError = false; // возникла ли ошибка
        $countStrInsert = 0; // сколько строк вставилось успешно
        $countStrAll = count($dataFB); // сколько строк было в первоисточнике
        if ($countStrAll > 0) {
            // данные есть
            // сканировать полученные данные построчно
            foreach ($dataFB as $item) {
                // создать экземпляр модели
                $modelInsert = new $model();
                // сканировать полученные для импорта поля
                foreach ($fieldsMySQLImport as $field) {
                    $field = Str::upper($field);
                    $modelInsert->$field = $item->$field;
                }
                try {
                    $modelInsert->save();
                    $countStrInsert++;
                } catch (\Exception $e) {
                    $hasError = true;
                    continue;
                }
            }
        }
        // массив преобразовать в строчку
        $fieldsFB = implode(', ', $fieldsFB);
        $fieldsMySQL = implode(', ', $fieldsMySQL);
        $fieldsMySQLImport = implode(', ', $fieldsMySQLImport);

        // возвращаемый параметр
        $return = array('fieldsFB' => $fieldsFB, 'fieldsMySQL' => $fieldsMySQL, 'fieldsMySQLImport' => $fieldsMySQLImport, 'hasError' => $hasError, 'countStrAll' => $countStrAll, 'countStrInsert' => $countStrInsert);
        return $return;
    }
}

