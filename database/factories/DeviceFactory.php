<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Device::class, function (Faker $faker) {

    // сперва получить список id
    $user_id = \App\Models\User::pluck('id')->toArray();

    return [
        'user_id' => $faker->randomElement($user_id),
    ];
});