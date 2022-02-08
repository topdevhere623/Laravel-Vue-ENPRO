<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AssetGroupKindTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'value' => 'analysisGroup',
                'description' => 'Аналитическая группа',
            ],
            [
                'id' => 2,
                'value' => 'complianceGroup',
                'description' => 'Группа аудита',
            ],
            [
                'id' => 3,
                'value' => 'functionalGroup',
                'description' => 'Функциональная группа',
            ],
            [
                'id' => 4,
                'value' => 'inventoryGroup',
                'description' => 'Группа оборудования',
            ],
            [
                'id' => 5,
                'value' => 'other',
                'description' => 'Прочие группы',
            ],
        ];
        DB::table('asset_group_kind')->insert($data);
    }
}
