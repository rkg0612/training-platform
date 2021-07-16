<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;

class SecretShopperService
{
    private $user;
    private $role;
    private $roleUser;

    const SECRET_SHOPPER = 'Secret Shopper';

    public function __construct(User $user, Role $role, RoleUser $roleUser)
    {
        $this->user = $user;
        $this->role = $role;
        $this->roleUser = $roleUser;
    }

    public function index()
    {
        $secretShoppers = $this->user->whereHas('roles', function ($query) {
            $query->where('friendly_name', '=', self::SECRET_SHOPPER);
        })->get();

        return response()->json($secretShoppers);
    }
}
