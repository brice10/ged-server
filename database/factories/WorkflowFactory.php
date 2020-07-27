<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Workflow\Workflow;
use Faker\Generator as Faker;

$factory->define(Workflow::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence(),
        'status' => $faker->randomElement(['APPROVED', 'PENDING', 'REJECTED']),
        'original_file' => $faker->text(20),
        'treated_file' => $faker->text(20),
        'track_id' => $faker->numberBetween(2000, 4000)
    ];
});
