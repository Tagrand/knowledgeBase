<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Argument;
use Faker\Generator as Faker;

$factory->define(Argument::class, function (Faker $faker) {
    return [
        'reason' => $faker->text(100),
    ];
});
