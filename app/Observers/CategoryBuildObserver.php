<?php

namespace App\Observers;

use App\Models\CategoryBuild;

class CategoryBuildObserver
{
    /**
     * Handle the category build "created" event.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return void
     */
    public function created(CategoryBuild $categoryBuild)
    {
        //
    }

    /**
     * Handle the category build "updated" event.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return void
     */
    public function updated(CategoryBuild $categoryBuild)
    {
        //
    }

    /**
     * Handle the category build "deleted" event.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return void
     */
    public function deleted(CategoryBuild $categoryBuild)
    {
        $categoryBuild->modules()->forceDelete();
    }

    /**
     * Handle the category build "restored" event.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return void
     */
    public function restored(CategoryBuild $categoryBuild)
    {
        //
    }

    /**
     * Handle the category build "force deleted" event.
     *
     * @param  \App\CategoryBuild  $categoryBuild
     * @return void
     */
    public function forceDeleted(CategoryBuild $categoryBuild)
    {
        //
    }
}
