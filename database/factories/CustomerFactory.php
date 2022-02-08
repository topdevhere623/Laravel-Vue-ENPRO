<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Customer::class, function (Faker $faker) {

    // сперва получить список id
    $acline_id = \App\Models\Acline::pluck('id')->toArray();

    return [
        'acline_id' => $faker->randomElement($acline_id),
    ];
});