<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EnproVehicle;
use Faker\Generator as Faker;

$factory->define(EnproVehicle::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence
    ];
});
