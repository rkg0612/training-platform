<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Broadcasting\NotificationChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user-notification-{id}', function () {
    return Auth::check();
});
