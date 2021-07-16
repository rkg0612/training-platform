<?php

namespace App\Observers;

use App\Models\FeaturedVideo;
use App\Notifications\VideoOfTheDay;
use App\Services\Notification\GetUsersList;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

class FeaturedVideoObserver
{
    private $getUsersList;

    public function __construct(GetUsersList $getUsersList)
    {
        $this->getUsersList = $getUsersList;
    }

    /**
     * Handle the featured video "created" event.
     *
     * @param  \App\Models\FeaturedVideo  $featuredVideo
     * @return void
     */
    public function created(FeaturedVideo $featuredVideo)
    {
        Notification::send(
            $this->getUsersList->getUsersBasedOnRolesName(['account-manager', 'specific-dealer-manager', 'salesperson']),
            (new VideoOfTheDay('A new video of the day has been posted!', '/client/lms#video-of-the-day'))
                ->delay($featuredVideo->start_at)
        );

        Artisan::call('lms:video_notification');
    }

    /**
     * Handle the featured video "updated" event.
     *
     * @param  \App\Models\FeaturedVideo  $featuredVideo
     * @return void
     */
    public function updated(FeaturedVideo $featuredVideo)
    {
        //
    }

    /**
     * Handle the featured video "deleted" event.
     *
     * @param  \App\Models\FeaturedVideo  $featuredVideo
     * @return void
     */
    public function deleted(FeaturedVideo $featuredVideo)
    {
        //
    }

    /**
     * Handle the featured video "restored" event.
     *
     * @param  \App\Models\FeaturedVideo  $featuredVideo
     * @return void
     */
    public function restored(FeaturedVideo $featuredVideo)
    {
        //
    }

    /**
     * Handle the featured video "force deleted" event.
     *
     * @param  \App\Models\FeaturedVideo  $featuredVideo
     * @return void
     */
    public function forceDeleted(FeaturedVideo $featuredVideo)
    {
        //
    }
}
