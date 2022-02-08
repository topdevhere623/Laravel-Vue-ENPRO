<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SpanTableSeeder extends Seeder
{
    public function run()
    {
        // мои данные
        for ($i = 1; $i <= 5; $i++) {

            $myId = $i;
            $myIOId = $i + 24; // в IO id = 25, 26, 27, 28, 29

            $data = factory(\App\Models\Span::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            \App\Models\Span::insert($data);
        }
    }
}