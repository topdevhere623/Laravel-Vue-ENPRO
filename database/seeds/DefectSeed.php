<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefectSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Добавляем классы дефектов
        $path = __DIR__ . '/sql/enpro_class_defect.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = __DIR__ . '/sql/enpro_group_defect.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = __DIR__ . '/sql/enpro_defect.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
