<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 30; $i++) {
            $data = factory(\App\Models\File::class, 1)->make()->each(function ($content) use ($i) {
                $content->title = 'Файл - ' . $i;
                $content->src = 'demo.jpg';
            })->toArray();
            \App\Models\File::insert($data);
        }
    }
}