<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Acline::class, function (Faker $faker) {

    // сперва получить список id
    $connector_id = \App\Models\Connector::pluck('id')->toArray();

    return [
        'connector_id' => $faker->randomElement($connector_id),
    ];
});