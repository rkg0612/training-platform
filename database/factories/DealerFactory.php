<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Dealer;
use Faker\Generator as Faker;

$factory->define(Dealer::class, function (Faker $faker) {
    return [
        'name'    => $faker->company,
        'website' => $faker->url,
        'address' => $faker->address,
        'is_automotive' => 1,
    ];
});
