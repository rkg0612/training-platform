<?php

namespace App\Providers;

use App\Helpers\ActiveInactiveFilter;
use App\Helpers\SearchModel;
use App\Models\PhoneShop;
use App\Observers\PhoneShopObserver;
use Illuminate\Support\ServiceProvider;

class GroupShopServiceProvider extends ServiceProvider
{
    use SearchModel, ActiveInactiveFilter;

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
        $this->allRecords();
        $this->onlyTrashed();
        $this->handleSearch();
        $this->withoutTrashed();
    }
}
