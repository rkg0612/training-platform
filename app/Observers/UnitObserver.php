<?php

namespace App\Observers;

use App\Models\Unit;

class UnitObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param  Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        //
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {
        //
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  Unit  $unit
     * @return void
     */
    public function deleted(Unit $unit)
    {
        foreach ($unit->relatedVideoOfTheDay()->get() as $videoOfTheDay) {
            $videoOfTheDay->delete();
        }
    }

    /**
     * Handle the category "restored" event.
     *
     * @param  Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        //
    }

    /**
     * Handle the category "force deleted" event.
     *
     * @param  Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        //
    }
}
