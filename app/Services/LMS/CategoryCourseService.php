<?php

namespace App\Services\LMS;

use App\Models\CategoryCourse;

class CategoryCourseService
{
    public function store($courses, $categoryId)
    {
        collect($courses)->each(function ($course) use ($categoryId) {
            CategoryCourse::create([
                'course_id' => $course,
                'category_id' => $categoryId,
            ]);
        });
    }

    public function update($courses, $categoryId)
    {
        $records = CategoryCourse::where('category_id', $categoryId)->pluck('course_id');
        $records->each(function ($record) use ($courses, $categoryId) {
            if (! in_array($record, $courses->toArray())) {
                CategoryCourse::where('category_id', $categoryId)
                    ->where('course_id', $record)
                    ->delete();
            }
        });
        $courses->each(function ($course) use ($records, $categoryId) {
            if (! in_array($course, $records->toArray())) {
                CategoryCourse::create([
                    'category_id' => $categoryId,
                    'course_id' => $course,
                ]);
            }
        });
    }
}
