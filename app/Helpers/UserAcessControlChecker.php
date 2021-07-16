<?php

namespace App\Helpers;

trait UserAcessControlChecker
{
    public function isSuperAdministrator()
    {
        return auth()->user()->roles->contains('name', \App\Models\Role::SUPER_ADMINISTRATOR);
    }

    public function isSecretShopper()
    {
        return auth()->user()->roles->contains('name', \App\Models\Role::SECRET_SHOPPER);
    }

    public function isAccountManager()
    {
        return auth()->user()->roles->contains('name', \App\Models\Role::ACCOUNT_MANAGER);
    }

    public function isSalesperson()
    {
        return auth()->user()->roles->contains('name', \App\Models\Role::SALESPERSON);
    }

    public function isSpecificDealerManager()
    {
        return auth()->user()->roles->contains('name', \App\Models\Role::SPECIFIC_DEALER_MANAGER);
    }
}
