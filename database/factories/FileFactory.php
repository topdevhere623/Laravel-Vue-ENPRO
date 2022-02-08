<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\File::class, function (Faker $faker) {

    // сперва получить список id
    $picturetype_id = \App\Models\Picturetype::pluck('id')->toArray();

    return [
        'type' => $faker->word,
        'description' => $faker->text(255),
        'picturetype_id' => $faker->randomElement($picturetype_id),
    ];
});