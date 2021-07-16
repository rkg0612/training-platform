<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Module;
use Faker\Generator as Faker;

$factory->define(Module::class, function (Faker $faker) {
    return [
        'value' => $faker->word,
        'banner_link' => $faker->imageUrl(300, 400),
        'description' => $faker->realText(),
    ];
});
