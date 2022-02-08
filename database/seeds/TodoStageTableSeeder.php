<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TodoStageTableSeeder extends Seeder
{
    public function run()
    {
        // этапы задачи
        $data = [
            [
                'id' => 1,
                'name' => 'назначено',
                'sort' => 1,
            ],
            [
                'id' => 2,
                'name' => 'согласовано',
                'sort' => 2,
            ],
            [
                'id' => 3,
                'name' => 'утверждено',
                'sort' => 3,
            ],
            [
                'id' => 4,
                'name' => 'допущено',
                'sort' => 4,
            ],
            [
                'id' => 5,
                'name' => 'выполнено',
                'sort' => 5,
            ],
        ];
        try {
            DB::table('todo_stages')->insert($data);
        } catch (Exception $e) {
        }
    }
}
