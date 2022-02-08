<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\Task::class, function (Faker $faker) {

    // сперва получить список id
    //$user_id = \App\Models\User::pluck('id')->toArray();
    //$tasktype_id = \App\Models\Tasktype::pluck('id')->toArray();
    //$substation_id = \App\Models\Substation::pluck('id')->toArray();
    //$connector_id = \App\Models\Connector::pluck('id')->toArray();

    return [
        //'description' => $faker->text(255),
        //'user_id' => $faker->randomElement($user_id),
        //'tasktype_id' => $faker->randomElement($tasktype_id),
        //'substation_id' => $faker->randomElement($substation_id),
        //'connector_id' => $faker->randomElement($connector_id),
        'uuid' => $faker->regexify('[A-Za-z0-9]{36}'),
        'startdate' => $faker->date('Y-m-d'),
        'enddate' => $faker->date('Y-m-d'),
        'status' => $faker->boolean,
    ];
});