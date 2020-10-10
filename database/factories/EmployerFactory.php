<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
    return [
        'firstname' => $faker->text(8),
        'lastname' => $faker->text(8),
        'username' => $faker->text(7),
        'email' => $faker->text(30),
        'designation' => $faker->text(10),
    ];
});
