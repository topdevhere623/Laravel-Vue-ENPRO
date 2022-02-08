<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TodoStageFioPivotTableSeeder extends Seeder
{
    public function run()
    {
        // получить список задач
        $todo_id = \App\Models\Todo::pluck('id')->toArray();
        // получить список этапов
        $stage_id = \App\Models\TodoStage::pluck('id')->toArray();
        // получить список фио
        $fio_id = \App\Models\Fio::pluck('id')->toArray();

        foreach ($todo_id as $todo) {
            foreach ($stage_id as $stage) {
                $data = [
                    [
                        'todo_id' => $todo,
                        'stage_id' => $stage,
                        'fio_id' => $fio_id[array_rand($fio_id)],
                    ],
                    [
                        'todo_id' => $todo,
                        'stage_id' => $stage,
                        'fio_id' => $fio_id[array_rand($fio_id)],
                    ],
                ];
                DB::table('todo_stage_fio_pivots')->insert($data);
            }
        }
    }
}