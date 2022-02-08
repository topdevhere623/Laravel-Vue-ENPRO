<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TasktypeTableSeeder extends Seeder
{
    public function run()
    {

        try {
            $id = DB::table('tasktype')->insertGetId([
                'id' => "1",
                'title' => "Линия",
                'object' => "line",
            ]);
            $id = DB::table('tasktype')->insertGetId([
                'id' => "2",
                'title' => "Подстанция",
                'object' => "substation",
            ]);
        } catch (Exception $e) {
        }
    }
}
