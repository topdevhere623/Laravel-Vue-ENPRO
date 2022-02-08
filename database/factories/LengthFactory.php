<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Length;
use Faker\Generator as Faker;

$factory->define(Length::class, function (Faker $faker) {
    $multiplier_id = \App\Models\UnitMultiplier::all()->pluck('id')->toArray();
    $unit_id = \App\Models\UnitSymbol::all()->pluck('id')->toArray();
    return [
        'value' => $faker->randomFloat(4, 0, 800),
        'multiplier_id' => $faker->randomElement($multiplier_id),
        'unit_id' => $faker->randomElement($unit_id)
    ];
});
