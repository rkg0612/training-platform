<?php

namespace App\Services\LMS;

use App\Models\User;

class GetUserByRolesService
{
    public function get($roles)
    {
        return User::with('roles')->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();
    }
}
