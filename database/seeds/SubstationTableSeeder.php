<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubstationTableSeeder extends Seeder
{
    public function run()
    {
        // данные от Анатолия
        for ($i = 1; $i <= 3; $i++) {

            $myId = $i;
            $myIOId = $i; // в IO id = 1, 2, 3

            $data = factory(\App\Models\Substation::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                //$content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            \App\Models\Substation::insert($data);
        }

        // демо-данные
        if (false) {
            for ($i = 1; $i <= 30; $i++) {
                $data = factory(\App\Models\Substation::class, 1)->make()->each(function ($content) use ($i) {
                    $content->identifiedobject_id = $i; // здесь именно важно от 1 до 30, как в IO
                })->toArray();
                \App\Models\Substation::insert($data);
            }
        }
    }
}