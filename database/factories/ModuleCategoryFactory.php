<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ModuleCategory;
use Faker\Generator as Faker;

$factory->define(ModuleCategory::class, function (Faker $faker) {
    $category_id = $faker->randomElement(
      \App\Models\Category::pluck('id')->toArray()
    );

    return [
        'category_id' => $category_id,
    ];
});
