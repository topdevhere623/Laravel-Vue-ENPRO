<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Length;
use Faker\Generator as Faker;
$addModels = [
    \App\Models\Reactance::class,
    \App\Models\Resistance::class,
    \App\Models\Susceptance::class,
    \App\Models\Temperature::class,
    \App\Models\Conductance::class,
    \App\Models\CurrentFlow::class,
];
foreach ($addModels as $model) {
    $factory->define($model,  function (Faker $faker) {
        $multiplier_id = \App\Models\UnitMultiplier::all()->pluck('id')->toArray();
        $unit_id = \App\Models\UnitSymbol::all()->pluck('id')->toArray();
        return [
            'value' => $faker->randomFloat(4, 0, 800),
            'multiplier_id' => $faker->randomElement($multiplier_id),
            'unit_id' => $faker->randomElement($unit_id)
        ];
    });
}

