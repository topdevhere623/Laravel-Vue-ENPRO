<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Tower::class, function (Faker $faker) {

    // сперва получить список id
    $acline_id = \App\Models\Acline::pluck('id')->toArray();
    $towermaterial_id = \App\Models\Towermaterial::pluck('id')->toArray();
    $towerkind_id = \App\Models\Towerkind::pluck('id')->toArray();
    //$towerinfo_id = \App\Models\Towerinfo::pluck('id')->toArray();

    return [
        'acline_id' => $faker->randomElement($acline_id),
        'towermaterial_id' => $faker->randomElement($towermaterial_id),
        'towerkind_id' => $faker->randomElement($towerkind_id),
        //'towerinfo_id' => $faker->randomElement($towerinfo_id),
    ];
});