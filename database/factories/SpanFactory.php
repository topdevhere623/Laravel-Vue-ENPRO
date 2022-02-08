<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Span::class, function (Faker $faker) {

    // сперва получить список id
    //$tower_id = \App\Models\Tower::pluck('id')->toArray();

    return [
        'spantype' => $faker->randomElement([701, 702]),
        //'startIO_id' => $faker->randomElement($tower_id),
        //'endIO_id' => $faker->randomElement($tower_id),
    ];
});