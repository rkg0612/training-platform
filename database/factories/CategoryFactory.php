<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'value' => $faker->word,
        'banner_link' => $faker->imageUrl(300, 400),
    ];
});
