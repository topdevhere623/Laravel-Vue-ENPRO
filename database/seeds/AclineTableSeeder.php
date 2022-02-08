<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AclineTableSeeder extends Seeder
{
    public function run()
    {
        // мои данные
        for ($i = 1; $i <= 3; $i++) {

            $myId = $i;
            $myIOId = $i + 21; // в IO id = 22, 23, 24

            $data = factory(\App\Models\Acline::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            \App\Models\Acline::insert($data);
        }
    }
}