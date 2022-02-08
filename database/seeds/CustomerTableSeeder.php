<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CustomerTableSeeder extends Seeder
{
    public function run()
    {
        // мои данные
        for ($i = 1; $i <= 5; $i++) {

            $myId = $i;
            $myIOId = $i + 29; // в IO id = 30, 31, 32, 33, 34

            $data = factory(\App\Models\Customer::class, 1)->make()->each(function ($content) use ($myId, $myIOId) {
                $content->id = $myId;
                $content->identifiedobject_id = $myIOId; // здесь именно важно как в IO
            })->toArray();
            \App\Models\Customer::insert($data);
        }
    }
}