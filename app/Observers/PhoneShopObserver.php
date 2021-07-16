<?php

namespace App\Observers;

use App\Models\PhoneShop;
use App\Notifications\PhoneShop\CreateRecord;
use App\Notifications\PhoneShop\DeleteRecord;
use App\Notifications\PhoneShop\RestoreRecord;
use App\Notifications\PhoneShop\UpdateRecord;
use App\Services\Notification\GetUsersList;
use Illuminate\Support\Facades\Notification;

class PhoneShopObserver
{
    private $getUsersList;

    public function __construct(GetUsersList $getUsersList)
    {
        $this->getUsersList = $getUsersList;
    }

    /**
     * Handle the phone shop "created" event.
     *
     * @param  PhoneShop  $phoneShop
     * @return void
     */
    public function created(PhoneShop $phoneShop)
    {
    }

    /**
     * Handle the phone shop "updated" event.
     *
     * @param  PhoneShop  $phoneShop
     * @return void
     */
    public function updated(PhoneShop $phoneShop)
    {
    }

    /**
     * Handle the phone shop "deleted" event.
     *
     * @param  PhoneShop  $phoneShop
     */
    public function deleted(PhoneShop $phoneShop)
    {
        $phoneShop->competitorRecordings()->get()->each(function ($recording) {
            $recording->delete();
        });
        $phoneShop->dealerRecordings()->get()->each(function ($recording) {
            $recording->delete();
        });
    }

    /**
     * Handle the models phone shop "restored" event.
     *
     * @param  PhoneShop  $phoneShop
     * @return void
     */
    public function restored(PhoneShop $phoneShop)
    {
        $phoneShop->competitorRecordings()->withTrashed()->get()->each(function ($recording) {
            $recording->restore();
        });
        $phoneShop->dealerRecordings()->withTrashed()->get()->each(function ($recording) {
            $recording->restore();
        });
    }
}
