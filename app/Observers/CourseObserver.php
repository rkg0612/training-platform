<?php

namespace App\Observers;

use App\Models\Course;

class CourseObserver
{
    /**
     * Handle the course "created" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function created(Course $course)
    {
        //
    }

    /**
     * Handle the course "updated" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function updated(Course $course)
    {
        //
    }

    /**
     * Handle the course "deleted" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        foreach ($course->categories()->get() as $category) {
            $category->delete();
        }
    }

    /**
     * Handle the course "restored" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function restored(Course $course)
    {
        foreach ($course->categories()->withTrashed()->get() as $category) {
            $category->restore();
        }
    }

    /**
     * Handle the course "force deleted" event.
     *
     * @param  \App\Models\Course  $course
     * @return void
     */
    public function forceDeleted(Course $course)
    {
        //
    }
}
