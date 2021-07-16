<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PhoneNumber;
use Faker\Generator as Faker;

$factory->define(PhoneNumber::class, function (Faker $faker) {
    return [
        'state_id' => $faker->randomElement(
            \App\Models\State::has('cities')->pluck('id')->toArray()
        ),
        'area_codes' => $faker->areaCode,
        'value' => $faker->phoneNumber,
        'dealer_id' => $faker->randomElement(
            \App\Models\Dealer::pluck('id')->toArray()
        ),
        'voice_mail_id' => $faker->randomElement(
            \App\Models\VoiceMail::pluck('id')->toArray()
        ),
        'is_dealer' => $faker->boolean(60),
        'user_id' => $faker->randomElement(
            \App\Models\User::with('roles')->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Manager', 'Salesperson']);
            })->pluck('id')->toArray()
        ),
    ];
});
