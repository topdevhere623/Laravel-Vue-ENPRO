<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BuildingTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Building::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'Здание - ' . $i;
            })->toArray();
            \App\Models\Building::insert($data);
        }
    }
}