<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EquipmentContainer;
use Faker\Generator as Faker;

$factory->define(EquipmentContainer::class, function (Faker $faker) {
    return [
        //
    ];
});
$factory->afterCreating(EquipmentContainer::class, function (EquipmentContainer $container, Faker $faker) {
    $container->getMRID();
});
