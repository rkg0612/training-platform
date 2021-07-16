s|<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Dealer;
use App\Models\SpecificDealer;
use Faker\Generator as Faker;

$factory->define(SpecificDealer::class, function (Faker $faker) {
    $dealer_id = $faker->randomElement(
        Dealer::pluck('id')->toArray()
    );

    return [
        'name' => $faker->name,
        'dealer_id' => $dealer_id,
    ];
});
