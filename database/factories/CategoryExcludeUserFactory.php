<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CategoryExcludeUser;
use Faker\Generator as Faker;

$factory->define(CategoryExcludeUser::class, function (Faker $faker) {
    $user_id = $faker->randomElement(
        \App\Models\User::with('roles')->whereHas('roles', function ($query) {
            $query->whereIn('name', ['Manager', 'Salesperson']);
        })->pluck('id')
    );

    return [
        'user_id' => $user_id,
    ];
});
