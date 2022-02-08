<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ConnectorFilePivotTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            $data = factory(\App\Models\ConnectorFilePivot::class, 1)->make()->each(function ($content) use ($i) {
                $content->connector_id = $i;
                $content->file_id = $i;
            })->toArray();
            \App\Models\ConnectorFilePivot::insert($data);
        }
    }
}