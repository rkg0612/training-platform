<?php

namespace App\Providers;

use App\Models\FeaturedVideo;
use App\Observers\FeaturedVideoObserver;
use Illuminate\Support\ServiceProvider;

class FeaturedVideoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        FeaturedVideo::observe(FeaturedVideoObserver::class);
    }
}
