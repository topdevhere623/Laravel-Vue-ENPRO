<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Address::class, function (Faker $faker) {

    // сперва получить список id
    $building_id = \App\Models\Building::pluck('id')->toArray();

    return [
        'settlement' => $faker->address,
        'district' => $faker->name,
        'projectpresence' => $faker->name,
        'building_id' => $faker->randomElement($building_id),
    ];
});