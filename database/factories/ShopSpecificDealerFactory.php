<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ShopSpecificDealer;
use Faker\Generator as Faker;

$factory->define(ShopSpecificDealer::class, function (Faker $faker) {
    return [
        'dealer_id' => \App\Models\Dealer::all()->random(10)->first()->id,
        'name' => $faker->company,
    ];
});
