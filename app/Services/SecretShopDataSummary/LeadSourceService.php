<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\InternetShop;

class LeadSourceService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        return $this->internetShop
            ->where('dealer_id', auth()->user()->dealer_id)
            ->pluck('lead_source')
            ->unique()
            ->values();
    }
}
