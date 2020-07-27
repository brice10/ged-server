<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Person\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->text(25),
        'display_name' => $faker->unique()->name,
        'description' => $faker->sentence,
    ];
});
