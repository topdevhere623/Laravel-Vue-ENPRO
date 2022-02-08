<?php

use App\Models\Bay;
use App\Models\Classname;
use App\Models\Role;
use App\Models\Subclass;
use App\Models\UnitMultiplier as UnitMultiplierAlias;
use App\Models\UnitSymbol;
use App\Models\Voltage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssetTableFakeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        //Добавляем фэйковые данные в простые таблицы
        /*
        for($i=0;$i<10;$i++) {
            DB::table('gost')->insert([
                "name" => $faker->name,
                "keylink" => $faker->text()
            ]);

            DB::table('manufacturer')->insert([
                "name" => $faker->name,
                "keylink" => $faker->title,
                "tags" =>  $faker->title,
                "manufaddress" => $faker->address
            ]);

            DB::table('bayinfo')->insert([
                "name" => $faker->title
            ]);

            DB::table('role')->insert([
                "name" => $faker->title
            ]);

            DB::table('voltages')->insert([
                "multiplier_id" => UnitMultiplierAlias::query()->inRandomOrder()->first()->id,
                "unit_id" => UnitSymbol::query()->inRandomOrder()->first()->id,
                "value" => 0
            ]);

            DB::table('subclass')->insert([
                "name" => $faker->title,
                "classname_id" => Classname::query()->inRandomOrder()->first()->id
            ]);
        }
        */

        //Добавляем фэйковые данные в тяжелые таблицы
        //не доделал. Выдает ошибку но вносит всего 1 запись. Пришлось вносить данные вручную
        for($i=0;$i<10;$i++) {
            $classname = Classname::query()->inRandomOrder()->first();
            DB::table('identifiedobject')->insert([
                "classname_id" => $classname->id,
                "subclass_id" => Subclass::query()->where('classname_id', $classname->id)->inRandomOrder()->first()->id,
                "voltage_id" => Voltage::query()->inRandomOrder()->first()->id,
                "aliasname"=>$faker->name,
                "mrid" =>$faker->title,
                //"address" => $faker->text,
                //"class_object" => $faker->jobTitle,
                "lat" => $faker->latitude,
                "long" => $faker->longitude,
                //"bay_id" => Bay::query()->inRandomOrder()->first()->id,
                "role_id" => Role::query()->inRandomOrder()->first()->id,
                "keylink" => $faker->uuid,
                //"description" => $faker->text,
                //"name" => $faker->name,
                //"localname" => $faker->name,

                /*
                 ,0 -- asset_id - BIGINT(20)
                 ,0 -- enobj_id - BIGINT(20)
                 ,0 -- subcontrollarea_id - BIGINT(20)
                 ,0 -- connector_id - BIGINT(20)
                 ,'' -- eqcid - VARCHAR(255)
                 ,0 -- entitytype - INT(11)
                 ,0 -- group - INT(11)
                 ,0 -- phaseno - INT(11)
                */
            ]);
        }
    }
}
