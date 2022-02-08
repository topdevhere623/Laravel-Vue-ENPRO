<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ConnectorTableSeeder extends Seeder
{
    public function run()
    {
        // данные от Анатолия
        for ($i = 1; $i <= 3; $i++) {

            $myId = $i;
            $myIOId = ($i + 3); // в IO id = 4, 5, 6

            // кол-во коннекторов - можно тогда и кол-во прикрепденных файлов увеличить к ним
            $data = factory(\App\Models\Connector::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            \App\Models\Connector::insert($data);
        }

        // демо-данные
        if (false) {
            // кол-во коннекторов - можно тогда и кол-во прикрепденных файлов увеличить к ним
            for ($i = 31; $i <= 60; $i++) {
                $data = factory(\App\Models\Connector::class, 1)->make()->each(function ($content) use ($i) {
                    $content->identifiedobject_id = $i; // здесь именно важно от 31 до 60, как в IO
                })->toArray();
                \App\Models\Connector::insert($data);
            }
        }
    }
}