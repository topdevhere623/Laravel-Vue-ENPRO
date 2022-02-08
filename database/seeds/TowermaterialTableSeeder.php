<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TowermaterialTableSeeder extends Seeder
{
    public function run()
    {
        // вставить свои 3-и строчки, как Анатолий сказал
        $data = [
            [
                'name' => 'дерево',
            ],
            [
                'user_id' => 'железо',
            ],
            [
                'user_id' => 'железобетон',
            ],
        ];
        DB::table('towermaterial')->insert($data);

        // вставить данные, как в materialkind
        if (false) {
            $materialkind = \App\Models\Materialkind::all();
            foreach ($materialkind as $item) {
                $towermaterial = new \App\Models\Towermaterial();
                $towermaterial->name = $item->name;
                $towermaterial->save();
            }
        }

        // вставка демо-данных
        if (false) {
            for ($i = 1; $i <= 10; $i++) {
                $data = factory(\App\Models\Towermaterial::class, 1)->make()->each(function ($content) use ($i) {
                    $content->name = 'Материал опоры - ' . $i;
                })->toArray();
                \App\Models\Towermaterial::insert($data);
            }
        }
    }
}
