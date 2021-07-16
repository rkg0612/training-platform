<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\DealerOption;
use App\Models\Role;
use App\Models\SpecificDealer;

class SpecificDealerService
{
    public function index()
    {
        $user = auth()->user();
        $specificDealers = SpecificDealer::with(['managers'])
            ->where(function ($query) use ($user) {
                if ($user->roles->contains('name', Role::SUPER_ADMINISTRATOR) || $user->roles->contains('name', Role::SECRET_SHOPPER)) {
                    return;
                }

                $query->where('dealer_id', $user->dealer_id);
            })
            ->get();

        return response()->json($specificDealers);
    }
}
