<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GostTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Технология возведения вышек 4G ГОСТ 15649-2021',
            ],
            [
                'id' => 2,
                'name' => 'ГОСТ Р 52719-2007 ТРАНСФОРМАТОРЫ СИЛОВЫЕ',
            ],
            [
                'id' => 3,
                'name' => 'ГОСТ 31946-2012',
            ],
        ];
        try {
            DB::table('gost')->insert($data);
        } catch (Exception $e) {
        }
    }
}
