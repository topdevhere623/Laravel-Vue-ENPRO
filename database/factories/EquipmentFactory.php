<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Equipment;
use Faker\Generator as Faker;

$factory->define(Equipment::class, function (Faker $faker) {
    return [
        'normallyInService' => $faker->boolean,
        'networkAnalysisEnabled' => $faker->boolean,
        'inService' => $faker->boolean,
        'aggregate' => $faker->boolean
    ];
});
$factory->afterCreating(Equipment::class, function (Equipment $container, Faker $faker) {
    $container->getMRID();
});
