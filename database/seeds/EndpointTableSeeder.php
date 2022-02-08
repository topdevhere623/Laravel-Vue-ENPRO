<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EndpointTableSeeder extends Seeder
{
    public function run()
    {
        // данные от Анатолия
        for ($i = 1; $i <= 5; $i++) {
            switch ($i) {
                case 1:
                    $myConnectorId = 1;
                    break;
                case 2:
                    $myConnectorId = 2;
                    break;
                case 3:
                    $myConnectorId = 2;
                    break;
                case 4:
                    $myConnectorId = 3;
                    break;
                case 5:
                    $myConnectorId = 3;
                    break;
            }

            $myId = $i;
            $myIOId = ($i + 6); // в IO id = 7, 8, 9, 10, 11

            $data = factory(\App\Models\Endpoint::class, 1)->make()->each(function ($content) use ($myId, $myIOId, $myConnectorId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
                $content->connector_id = $myConnectorId;
                $content->status = 1;
            })->toArray();
            \App\Models\Endpoint::insert($data);
        }

        // демо-данные
        if (false) {
            for ($i = 61; $i <= 120; $i++) {
                $data = factory(\App\Models\Endpoint::class, 1)->make()->each(function ($content) use ($i) {
                    $content->identifiedobject_id = $i; // здесь именно важно от 61 до 120, как в IO
                    $content->name = 'Конечная точка - ' . ($i - 60);
                    $content->status = 1;
                })->toArray();
                \App\Models\Endpoint::insert($data);
            }
        }
    }
}