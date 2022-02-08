<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Todo::class, function (Faker $faker) {

    // сперва получить список id
    $status_id = \App\Models\TodoStatus::pluck('id')->toArray();

    return [
        'status_id' => $faker->randomElement($status_id),
    ];
});