<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\InternetShop;

class SalesPersonService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        return $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
            ->pluck('salesperson_name');
    }
}
