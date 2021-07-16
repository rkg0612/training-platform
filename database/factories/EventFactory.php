<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Event;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $colors = ['red', 'blue', 'green'];

    return [
        'name'     => $faker->word(5),
        'url'      => $faker->url,
        'start_at' => Carbon::now(),
        'end_at'   => Carbon::now()->addMonth(rand(0, 5)),
        'color'    => $colors[rand(0, 2)],
        'user_id' => 1,
    ];
});
