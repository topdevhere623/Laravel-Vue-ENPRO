<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AssetTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Asset::class, 1)->make()->each(function ($content) use ($i) {

            })->toArray();
            \App\Models\Asset::insert($data);
        }
    }
}