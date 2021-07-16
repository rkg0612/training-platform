<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\InternetShop;
use App\Models\State;

class StateService
{
    public $state;
    public $internetShop;

    public function __construct(State $state, InternetShop $internetShop)
    {
        $this->state = $state;
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        return $this->state->whereIn('id',
            $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
                ->pluck('state_id')
                ->unique()
                ->toArray()
        )->get();
    }
}
