<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Fio::class, function (Faker $faker) {

    // сперва получить список id
    $company_id = \App\Models\Company::pluck('id')->toArray();

    return [
        'company_id' => $faker->randomElement($company_id),
    ];
});