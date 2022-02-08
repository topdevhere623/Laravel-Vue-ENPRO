<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TowerTableSeeder extends Seeder
{
    public function run()
    {
        // мои данные
        for ($i = 1; $i <= 5; $i++) {

            $myId = $i;
            $myIOId = $i + 11; // в IO id = 12, 13, 14, 15, 16, 17, 18, 19, 20, 21

            $data = factory(\App\Models\Tower::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            try {
                \App\Models\Tower::insert($data);
            } catch (Exception $e) {
            }
        }
    }
}
