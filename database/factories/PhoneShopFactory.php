<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\City;
use App\Models\PhoneShop;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(PhoneShop::class, function (Faker $faker) {
    $state_id = $faker->randomElement(
        State::has('cities')->pluck('id')->toArray()
    );
    $city_id = $faker->randomElement(
        City::where('state_id', $state_id)->pluck('id')->toArray()
    );
    $user_id = $faker->randomElement(
        User::whereHas('roles', function ($query) {
            $query->where('name', 'secret shopper');
        })->pluck('id')->toArray()
    );

    return [
        'secret_shopper_id' => $user_id,
        'state_id' => $state_id,
        'city_id' => $city_id,
        'shop_type' => $faker->boolean(60),
        'sales_person_name' => $faker->name,
        'lead_name' => $faker->name('female'),
        'start_date' => Carbon::now()->subDay(rand(1, 5)),
        'inbound_call_grade' => $faker->randomHtml(2, 3),
    ];
});
