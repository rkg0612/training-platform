<?php

/** @var Factory $factory */

use App\Models\City;
use App\Models\InternetShop;
use App\Models\SpecificDealer;
use App\Models\State;
use App\Models\User;
use Faker\Generator as Faker;
use Faker\Provider\Fakecar;
use Illuminate\Database\Eloquent\Factory;

$factory->define(InternetShop::class, function (Faker $faker) {
    $faker->addProvider(new Fakecar($faker));
    $vehicle = $faker->vehicleArray();
    $user = User::withoutGlobalScopes()->whereHas('dealer', function ($query) {
        $query->where('is_automotive', 1);
    })->first();
    $state_id = $faker->randomElement(
        State::has('cities')->pluck('id')->toArray()
    );
    $city_id = $faker->randomElement(
        City::where('state_id', $state_id)->pluck('id')->toArray()
    );

    return [
        'user_id' => $user->id,
        'is_dealer' => 1,
        'dealer_id' => $user->dealer_id,
        'specific_dealer_id' => $faker->randomElement(SpecificDealer::where('dealer_id', $user->dealer_id)->pluck('id')->toArray()),
        'timezone' => 'EST',
        'state_id' => $state_id,
        'city_id' => $city_id,
        'is_shop_new' => $faker->boolean(70),
        'lead_source' => $faker->randomElement(['TrueCar', 'Dealer Site', 'Carnow']),
        'source_link' => $faker->url,
        'vehicle_identification_number' => $faker->vin,
        'make' => $vehicle['brand'],
        'model' => $vehicle['model'],
        'lead_name' => $faker->name,
        'lead_email' => $faker->companyEmail,
        'lead_phone_number' => $faker->phoneNumber,
        'salesperson_name' => $faker->name,
        'csm_name' => '',
        'call_guide_link' => $faker->url,
        'start_at' => $faker->dateTimeThisMonth,
        'shop_duration' => $faker->time(),
        'call_first_received' => $faker->dateTimeThisMonth(),
        'call_response_time' => $faker->time(),
        'call_attempts' => $faker->randomNumber(),
        'sms_first_received' => $faker->dateTimeThisMonth(),
        'sms_response_time' => $faker->time(),
        'sms_attempts' => $faker->randomNumber(),
        'email_first_received' => $faker->dateTimeThisMonth(),
        'email_response_time' => $faker->time(),
        'email_attempts' => $faker->randomNumber(),
        'email_second_received' => $faker->dateTimeThisMonth(),
        'email_second_response_time' => $faker->time(),
        'chat_first_received' => $faker->dateTimeThisMonth(),
        'chat_response_time' => $faker->time(),
        'chat_attempts' => $faker->randomNumber(),
        'csm_name' => $faker->userName,
    ];
});
