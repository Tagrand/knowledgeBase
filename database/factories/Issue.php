<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Issue;
use Faker\Generator as Faker;

$factory->define(Issue::class, function (Faker $faker) {
    return [
        'name' => $faker->text(100),
        'summary' => $faker->text(200),
    ];
});
