<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TodoTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Todo::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'Задача - ' . $i;
                $content->description = 'описание - ' . $i;
            })->toArray();
            try {
                \App\Models\Todo::insert($data);
            } catch (Exception $e) {
            }
        }
    }
}
