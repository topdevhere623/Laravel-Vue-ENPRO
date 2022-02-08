<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Endpoint::class, function (Faker $faker) {

    // сперва получить список id
    $acline_id = \App\Models\Acline::pluck('id')->toArray();
    //$connector_id = \App\Models\Connector::pluck('id')->toArray();

    return [
        'acline_id' => $faker->randomElement($acline_id),
        //'connector_id' => $faker->randomElement($connector_id),
    ];
});