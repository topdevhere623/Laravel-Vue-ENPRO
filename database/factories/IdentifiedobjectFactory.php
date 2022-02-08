<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Identifiedobject::class, function (Faker $faker) {

    // сперва получить список id
    $basevoltage_id = \App\Models\BaseVoltage::pluck('id')->toArray();

    return [
        'voltage_id' => 380, // конкретно на переменный 0,4 кВ // $faker->randomElement($basevoltage_id),
        //'lat' => $faker->numberBetween($min = 57000, $max = 57999) / 1000,
        //'long' => $faker->numberBetween($min = 61000, $max = 61999) / 1000,
        'phaseno' => $faker->numberBetween($min = 3, $max = 4),
        'subclass_id' => $faker->numberBetween($min = 701, $max = 702),
        //'address' => $faker->address,
    ];
});
