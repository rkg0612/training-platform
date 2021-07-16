<?php

namespace App\Services;

use App\Models\SpecificDealer;

class GetSpecificDealerService
{
    public function fetch($id)
    {
        return SpecificDealer::query()
            ->where('dealer_id', $id)
            ->get();
    }
}
