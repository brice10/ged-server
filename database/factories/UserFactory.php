<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Person\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'login' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'first_name' => $faker->firstName(),
        'last_name' => $faker->firstName(),
        'birth_date' => $faker->date(),
        'birth_place' => $faker->streetName(),
        'tel' => $faker->phoneNumber(),
        'job' => $faker->text(10),
        'language' => $faker->languageCode(),
        'gender' => $faker->randomElement(['F', 'M']),
        'password' => bcrypt('pwd'),
        'remember_token' => Str::random(10),
    ];

});
