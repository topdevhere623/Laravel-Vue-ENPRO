<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TaskTableSeeder extends Seeder
{
    public function run()
    {
        // данные от Анатолия
        for ($i = 1; $i <= 3; $i++) {

            $mySubstationId = $i;
            $myConnectorId = $i;

            $data = factory(\App\Models\Task::class, 1)->make()->each(function ($content) use ($i, $mySubstationId, $myConnectorId) {
                $content->title = 'Задача - ' . $i;
                $content->description = 'Описание задачи - ' . $i;
                $content->substation_id = $mySubstationId;
                $content->connector_id = $myConnectorId;
                $content->tasktype_id = 1; // линия
                $content->user_id = 2; // как в Приложение на Клиенте
            })->toArray();
            \App\Models\Task::insert($data);
        }

        // демо-данные
        if (false) {
            // кол-во задач - можно тогда и кол-во прикрепденных файлов увеличить к ним
            for ($i = 1; $i <= 60; $i++) {
                $data = factory(\App\Models\Task::class, 1)->make()->each(function ($content) use ($i) {
                    $content->title = 'Задача - ' . $i;
                })->toArray();
                \App\Models\Task::insert($data);
            }
        }
    }
}