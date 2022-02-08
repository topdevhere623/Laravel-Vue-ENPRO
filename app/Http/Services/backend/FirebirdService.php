<?php

namespace App\Http\Services\backend;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// сервис работы с БД Firebird
class FirebirdService
{
    // список всех таблиц
    public function tables()
    {
        // запрос
        $sql = 'select RDB$RELATION_NAME as name from RDB$RELATIONS where (RDB$SYSTEM_FLAG = 0) AND (RDB$RELATION_TYPE = 0) order by RDB$RELATION_NAME';
        // выполнение запроса
        $return = DB::connection('myFirebirdConnection')->select($sql);
        // возвращаемый параметр
        return $return;
    }

    // список полей для таблицы
    public function fields($table)
    {
        // запрос
        $sql = 'select RDB$FIELD_NAME as name from rdb$relation_fields where RDB$RELATION_NAME  = \'' . $table . '\'';
        // выполнение запроса
        $query = DB::connection('myFirebirdConnection')->select($sql);
        $return = [];
        foreach ($query as $item) {
            $return [] = trim($item->NAME);
        }
        // возвращаемый параметр
        return $return;
    }

    // получение списка всех таблиц и их полей
    public function tablesWithFields($tables, $tabls_maska, $need_fields)
    {
        $return = [];
        foreach ($tables as $item) {

            // имя таблицы
            $table = trim(Str::upper($item->NAME));
            // проверить, нужно ли ограничить по маске в имени
            if ($tabls_maska == 1) {
                if (!Str::contains($table, 'KIND') and !Str::contains($table, 'INFO')) continue;
            }

            // список полей для таблицы
            $fields = [];
            if ($need_fields == 1) {
                // поля нужны
                // список полей для таблицы
                $fields = self::fields($table);
            }
            // кол-во строк в таблице
            $count = self::count($table);

            // записать в массив
            $return[] = ['table' => $table, 'fields' => $fields, 'count' => $count, 'comment' => ''];
        }

        // возвращаемый параметр
        return $return;
    }

    // получение данных из указанной таблицы
    public function zaprosData($table, $fields = null)
    {
        // запрос
        if (is_null($fields)) {
            // список полней не передали
            $sql = 'select ' . '*' . ' from ' . $table;
        } else {
            // список полей передали
            // полученный список полей в строчку
            $fields = implode(', ', $fields);
            $sql = 'select ' . $fields . ' from ' . $table;
        }
        // выполнение запроса
        $return = DB::connection('myFirebirdConnection')->select($sql);
        // возвращаемый параметр
        return $return;
    }

    // кол-во строк в таблице
    public function count($table)
    {
        // выполнение запроса
        try {
            $query = DB::connection('myFirebirdConnection')->table($table)->select('*')->get();
        } catch (\Exception $e) {
            $query = [];
        }

        $return = 0;
        foreach ($query as $item) {
            $return++;
        }
        // возвращаемый параметр
        return $return;
    }
}