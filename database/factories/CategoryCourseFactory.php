<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CategoryCourse;
use Faker\Generator as Faker;

$factory->define(CategoryCourse::class, function (Faker $faker) {
    $course_id = $faker->randomElement(
        \App\Models\Category::pluck('id')->toArray()
    );

    return [
        'course_id' => $course_id,
    ];
});
