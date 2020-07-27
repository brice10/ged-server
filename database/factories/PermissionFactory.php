<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Person\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->text(25),
        'display_name' => $faker->unique()->name,
        'description' => $faker->sentence,
    ];
});
