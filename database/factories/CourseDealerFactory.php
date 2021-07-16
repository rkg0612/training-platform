<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CourseDealer;
use Faker\Generator as Faker;

$factory->define(CourseDealer::class, function (Faker $faker) {
    $dealer_id = $faker->randomElement(
        \App\Models\Dealer::pluck('id')->toArray()
    );

    return [
        'dealer_id' => $dealer_id,
    ];
});
