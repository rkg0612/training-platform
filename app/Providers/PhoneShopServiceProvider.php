<?php

namespace App\Providers;

use App\Helpers\ActiveInactiveFilter;
use App\Helpers\SearchModel;
use App\Models\PhoneShop;
use App\Observers\PhoneShopObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

/**
 * Class PhoneShopServiceProvider.
 */
class PhoneShopServiceProvider extends ServiceProvider
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
        PhoneShop::observe(PhoneShopObserver::class);
        $this->allRecords();
        $this->onlyTrashed();
        $this->handleSearch();
        $this->withoutTrashed();
    }
}
