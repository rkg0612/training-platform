<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\FeaturedVideo;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(FeaturedVideo::class, function (Faker $faker) {
    return [
        'title'    => $faker->word(5),
        'url'      => $faker->url,
        'start_at' => Carbon::now()->addMonth(1),
        'end_at'   => Carbon::now()->addMonth(2),
    ];
});
