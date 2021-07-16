<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\City;
use App\Models\InternetShop;

class CityService
{
    public $city;
    public $internetShop;

    public function __construct(City $city, InternetShop $internetShop)
    {
        $this->city = $city;
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        return $this->city->whereIn('id',
            $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
                ->pluck('city_id')
                ->unique()
                ->toArray()
        )->get();
    }
}
