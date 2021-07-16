<?php

namespace App\Providers;

use App\Models\InternetShop;
use App\Observers\InternetShopObserver;
use Illuminate\Support\ServiceProvider;

class InternetShopServiceProvider extends ServiceProvider
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
        InternetShop::observe(InternetShopObserver::class);
    }
}
