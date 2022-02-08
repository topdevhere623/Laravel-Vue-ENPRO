<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubstationinfoTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Substationinfo::class, 1)->make()->each(function ($content) use ($i) {
                $content->description = 'Инфо о подстанции - ' . $i;
            })->toArray();
            \App\Models\Substationinfo::insert($data);
        }
    }
}