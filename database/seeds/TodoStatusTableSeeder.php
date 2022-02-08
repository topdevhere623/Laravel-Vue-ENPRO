<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TodoStatusTableSeeder extends Seeder
{
    public function run()
    {
        // этапы задачи
        $data = [
            [
                'id' => 1,
                'name' => 'назначена',
                'sort' => 1,
            ],
            [
                'id' => 2,
                'name' => 'выполняется',
                'sort' => 2,
            ],
            [
                'id' => 3,
                'name' => 'выполнена',
                'sort' => 3,
            ],
            [
                'id' => 4,
                'name' => 'отменена',
                'sort' => 4,
            ],
        ];
        try {
            DB::table('todo_status')->insert($data);
        } catch (Exception $e) {
        }
    }
}
