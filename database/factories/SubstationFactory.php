<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Substation::class, function (Faker $faker) {

    // сперва получить список id
    //$acline_id = \App\Models\Acline::pluck('id')->toArray();
    //$address_id = \App\Models\Address::pluck('id')->toArray();
    $substationfunction_id = \App\Models\Substationfunction::pluck('id')->toArray();
    $substationinfo_id = \App\Models\Substationinfo::pluck('id')->toArray();

    return [
        //'acline_id' => $faker->randomElement($acline_id),
        'passport' => 'passport N' . $faker->numberBetween($min = 100, $max = 999),
        //'address_id' => $faker->randomElement($address_id),
        'substationfunction_id' => $faker->randomElement($substationfunction_id),
        'substationinfo_id' => $faker->randomElement($substationinfo_id),
    ];
});
