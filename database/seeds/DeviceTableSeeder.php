<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DeviceTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Device::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'Устройство планшет - ' . $i;
            })->toArray();
            \App\Models\Device::insert($data);
        }
    }
}