<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CompanyTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Company::class, 1)->make()->each(function ($content) use ($i) {
                $content->name = 'Организация - ' . $i;
                $content->description = 'описание - ' . $i;
                $content->phone = 'телефон - ' . $i;
                $content->address = 'адрес - ' . $i;
                $content->email = 'email - ' . $i;
                $content->web = 'сайт - ' . $i;
            })->toArray();
            \App\Models\Company::insert($data);
        }
    }
}