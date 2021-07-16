<?php

namespace App\Services;

use App\Models\State;
use Illuminate\Support\Arr;

class StateService
{
    public function getStates()
    {
        $states = State::with(['areaCodes'])->get()->toArray();
        $result = [];
        foreach ($states as $state) {
            $stateObject = $state;
            $areaCodes = array_column($state['area_codes'], 'value');
            $stateObject['area_codes'] = count($areaCodes) > 1 ? implode(', ', $areaCodes) : $areaCodes[0];
            $result[] = $stateObject;
        }

        return response()->json($result);
    }
}
