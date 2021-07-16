<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\InternetShop;

class CSMService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        return $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
            ->pluck('csm_name')->unique();
    }
}
