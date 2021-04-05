<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'price' => $faker->randomFloat(2,0,1000000),
        'status' => $faker->boolean(),
        'qty' => $faker->randomNumber('5')
    ];
});
