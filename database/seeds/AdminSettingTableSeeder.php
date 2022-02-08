<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AdminSettingTableSeeder extends Seeder
{
    public function run()
    {
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_title",
            'value' => "Электрические сети | 2021 г.",
            'comment' => "Текст на всех страницах в конце каждого тайтла",
            'sort' => 1,
        ]);
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_paginate_admin",
            'value' => "50",
            'comment' => "Кол-во записей на странице",
            'sort' => 2,
        ]);
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_timezone",
            'value' => "5",
            'comment' => "Корректировка часового пояса",
            'sort' => 3,
        ]);
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_map_center",
            'value' => "57.342954, 61.347649", // "57.373772, 61.391639" - это город Реж
            'comment' => "Центр карты карты по-умолчанию",
            'sort' => 4,
        ]);
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_map_scale",
            'value' => 17,
            'comment' => "Масштаб карты по-умолчанию для списков",
            'sort' => 5,
        ]);
        $id = DB::table('admin_settings')->insertGetId([
            'key' => "setting_map_scale_one",
            'value' => 17,
            'comment' => "Масштаб карты по-умолчанию для одиночного обьекта",
            'sort' => 6,
        ]);
    }
}

