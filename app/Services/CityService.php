<?php

namespace App\Services;

use App\Models\City;

class CityService
{
    public function show($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->get(['id', 'value']);

        return response()->json($cities);
    }
}
