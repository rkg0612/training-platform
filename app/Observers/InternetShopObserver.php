<?php

namespace App\Observers;

use App\Models\InternetShop;
use App\Notifications\InternetShop\CreateRecord;
use App\Notifications\InternetShop\DeleteRecord;
use App\Notifications\InternetShop\RestoreRecord;
use App\Notifications\InternetShop\UpdateRecord;
use App\Services\Notification\GetUsersList;
use Illuminate\Support\Facades\Notification;

class InternetShopObserver
{
    private $getUsersList;

    public function __construct(GetUsersList $getUsersList)
    {
        $this->getUsersList = $getUsersList;
    }

    /**
     * Handle the internet shop "created" event.
     *
     * @param InternetShop $internetShop
     * @return void
     */
    public function created(InternetShop $internetShop)
    {
    }

    /**
     * Handle the internet shop "updated" event.
     *
     * @param InternetShop $internetShop
     * @return void
     */
    public function updated(InternetShop $internetShop)
    {
    }

    /**
     * Handle the internet shop "deleted" event.
     *
     * @param InternetShop $internetShop
     * @return void
     */
    public function deleted(InternetShop $internetShop)
    {
        $internetShop->emailScreenshots()->get()->each(function ($emailScreenshot) {
            $emailScreenshot->delete();
        });
        $internetShop->emailScreenshots()->get()->each(function ($chatScreenshot) {
            $chatScreenshot->delete();
        });
    }

    /**
     * Handle the internet shop "restored" event.
     *
     * @param InternetShop $internetShop
     * @return void
     */
    public function restored(InternetShop $internetShop)
    {
    }
}
