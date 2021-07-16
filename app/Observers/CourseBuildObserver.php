<?php

namespace App\Observers;

use App\Models\CourseBuild;

class CourseBuildObserver
{
    /**
     * Handle the course build "created" event.
     *
     * @param  \App\Models\CourseBuild  $courseBuild
     * @return void
     */
    public function created(CourseBuild $courseBuild)
    {
        //
    }

    /**
     * Handle the course build "updated" event.
     *
     * @param  \App\Models\CourseBuild  $courseBuild
     * @return void
     */
    public function updated(CourseBuild $courseBuild)
    {
        //
    }

    /**
     * Handle the course build "deleted" event.
     *
     * @param  \App\Models\CourseBuild  $courseBuild
     * @return void
     */
    public function deleted(CourseBuild $courseBuild)
    {
        $courseBuild->category_builds()->forceDelete();
    }

    /**
     * Handle the course build "restored" event.
     *
     * @param  \App\Models\CourseBuild  $courseBuild
     * @return void
     */
    public function restored(CourseBuild $courseBuild)
    {
        //
    }

    /**
     * Handle the course build "force deleted" event.
     *
     * @param  \App\Models\CourseBuild  $courseBuild
     * @return void
     */
    public function forceDeleted(CourseBuild $courseBuild)
    {
        //
    }
}
