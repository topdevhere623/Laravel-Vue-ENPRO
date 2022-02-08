<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FioTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Fio::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'ФИО - ' . $i;
                $content->position = 'должность- ' . $i;
                $content->phone = 'телефон- ' . $i;
                $content->email = 'email- ' . $i;
            })->toArray();
            \App\Models\Fio::insert($data);
        }
    }
}