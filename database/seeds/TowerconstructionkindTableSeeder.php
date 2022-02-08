<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TowerconstructionkindTableSeeder extends Seeder
{
    public function run()
    {
        // вставить свои строчки (из скриншота)
        $data = [
            [
                'name' => 'Одностоечная',
            ],
            [
                'user_id' => 'П-образная (портальная)',
            ],
            [
                'user_id' => 'А-образная',
            ],
            [
                'user_id' => 'Т-образная',
            ],
            [
                'user_id' => 'А-П-образные',
            ],
            [
                'user_id' => 'Y-образные',
            ],
            [
                'user_id' => 'V-образные',
            ],
        ];
        DB::table('towerconstructionkind')->insert($data);
    }
}
