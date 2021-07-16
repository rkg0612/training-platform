<?php

namespace App\Observers;

use App\Models\ModuleBuild;

class ModuleBuildObserver
{
    /**
     * Handle the module build "created" event.
     *
     * @param  \App\ModuleBuild  $moduleBuild
     * @return void
     */
    public function created(ModuleBuild $moduleBuild)
    {
        //
    }

    /**
     * Handle the module build "updated" event.
     *
     * @param  \App\ModuleBuild  $moduleBuild
     * @return void
     */
    public function updated(ModuleBuild $moduleBuild)
    {
        //
    }

    /**
     * Handle the module build "deleted" event.
     *
     * @param  \App\ModuleBuild  $moduleBuild
     * @return void
     */
    public function deleted(ModuleBuild $moduleBuild)
    {
        if ($moduleBuild->isForceDeleting()) {
            foreach ($moduleBuild->series as $series) {
                $series->units()->forceDelete();
                $series->forceDelete();
            }
        } else {
            foreach ($moduleBuild->series as $series) {
                $series->units()->forceDelete();
                $series->forceDelete();
            }
        }
    }

    /**
     * Handle the module build "restored" event.
     *
     * @param  \App\ModuleBuild  $moduleBuild
     * @return void
     */
    public function restored(ModuleBuild $moduleBuild)
    {
        //
    }

    /**
     * Handle the module build "force deleted" event.
     *
     * @param  \App\ModuleBuild  $moduleBuild
     * @return void
     */
    public function forceDeleted(ModuleBuild $moduleBuild)
    {
        //
    }
}
