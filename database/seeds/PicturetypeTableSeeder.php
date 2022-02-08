<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PicturetypeTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Picturetype::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'Тип изображения - ' . $i;
            })->toArray();
            \App\Models\Picturetype::insert($data);
        }
    }
}