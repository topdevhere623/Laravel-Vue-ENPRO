<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AddressTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $data = factory(\App\Models\Address::class, 1)->make()->each(function ($content) use ($i) {
                $content->address = 'Адрес - ' . $i;
            })->toArray();
            \App\Models\Address::insert($data);
        }
    }
}