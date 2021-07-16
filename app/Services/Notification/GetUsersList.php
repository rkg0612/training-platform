<?php

namespace App\Services\Notification;

use App\Models\Dealer;
use App\Models\User;

class GetUsersList
{
    public $user;
    public $dealer;

    public function __construct(User $user, Dealer $dealer)
    {
        $this->user = $user;
        $this->dealer = $dealer;
    }

    public function getUsersBasedOnRolesName($roles)
    {
        return $this->user->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
    }

    public function getUsersByDealer($id)
    {
        return $this->dealer->find($id)->users;
    }
}
