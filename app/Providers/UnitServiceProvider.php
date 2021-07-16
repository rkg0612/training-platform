<?php

namespace App\Providers;

use App\Models\Unit;
use App\Observers\UnitObserver;
use Illuminate\Support\ServiceProvider;

class UnitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Unit::observe(UnitObserver::class);
    }
}
