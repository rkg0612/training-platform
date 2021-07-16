<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationChannel
{
    private $id;

    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user)
    {
        return User::findOrFail($this->id);
    }
}
