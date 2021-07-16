<?php

namespace App\Services\SecretShopDataSummary;

use App\Helpers\SecretShopSummaryReport;
use App\Models\InternetShop;
use App\Models\SpecificDealer;

class SpecificDealerService
{
    public $specificDealer;
    public $internetShop;
    public $secretShopHelper;

    public function __construct(SpecificDealer $specificDealer, InternetShop $internetShop, SecretShopSummaryReport $secretShopHelper)
    {
        $this->specificDealer = $specificDealer;
        $this->internetShop = $internetShop;
        $this->secretShopHelper = $secretShopHelper;
    }

    public function index()
    {
        $specificDealersId = $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
            ->pluck('specific_dealer_id')->unique()->toArray();

        return $this->specificDealer->whereIn('id', $specificDealersId)->get();
    }
}
