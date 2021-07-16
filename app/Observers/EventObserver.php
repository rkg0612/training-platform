<?php

namespace App\Observers;

use App\Models\Event;
use App\Notifications\EventCalendar;
use App\Services\Notification\GetUsersList;
use Illuminate\Support\Facades\Notification;

class EventObserver
{
    private $getUsersList;
    private $auth;

    public function __construct(GetUsersList $getUsersList)
    {
        $this->getUsersList = $getUsersList;
        $this->auth = auth()->user();
    }

    /**
     * Handle the event "created" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        if (! $this->auth->roles->contains('name', 'super-administrator') ||
            $this->auth->roles->contains('name', 'secret-shopper')) {
            $users = $this->auth->dealer->users;
        } else {
            $users = $this->getUsersList->getUsersBasedOnRolesName(
                ['account-manager', 'specific-dealer-manager', 'salesperson']
            );
        }

        Notification::send(
            $users,
            (new EventCalendar('A new event has been posted by '.auth()->user()->name, '/client/home#event-calendar'))
        );
    }

    /**
     * Handle the event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        //
    }

    /**
     * Handle the event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        //
    }

    /**
     * Handle the event "restored" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the event "force deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
