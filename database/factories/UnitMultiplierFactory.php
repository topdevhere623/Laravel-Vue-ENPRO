<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UnitMultiplier;
use Faker\Generator as Faker;

$factory->define(UnitMultiplier::class, function (Faker $faker) {
    return [
        'value' => $faker->numberBetween(-10000, 10000),
        'literal' => $faker->text(5),
        'description' => $faker->text()
    ];
});
