<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Connector::class, function (Faker $faker) {

    // сперва получить список id
    //$basevoltage_id = \App\Models\BaseVoltage::pluck('id')->toArray();

    return [
        //'basevoltage_id' => 380, // буду брать из IO // конкретно переменный на 0,4 кВ // $faker->randomElement($basevoltage_id),
        'img' => 'demo.jpg',
        'connected' => $faker->numberBetween($min = 0, $max = 1),
    ];
});
