<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TaskFilePivotTableSeeder extends Seeder
{
    public function run()
    {
        for ($j = 0; $j <= 0; $j++) {
            for ($i = 1; $i <= 3; $i++) {
                $data = factory(\App\Models\TaskFilePivot::class, 1)->make()->each(function ($content) use ($j, $i) {
                    $content->task_id = (30 * $j) + $i;
                    $content->file_id = $i;
                })->toArray();
                \App\Models\TaskFilePivot::insert($data);
            }
        }
    }
}