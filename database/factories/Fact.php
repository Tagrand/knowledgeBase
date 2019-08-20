<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Fact;
use Faker\Generator as Faker;

$factory->define(Fact::class, function (Faker $faker) {
    return [
        'claim' => $faker->text(100),
    ];
});
