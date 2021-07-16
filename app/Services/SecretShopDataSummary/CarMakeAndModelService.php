<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\InternetShop;

class CarMakeAndModelService
{
    public $internetShop;

    public function __construct(InternetShop $internetShop)
    {
        $this->internetShop = $internetShop;
    }

    public function index()
    {
        $internetShop = $this->internetShop->where('dealer_id', auth()->user()->dealer_id)->get();

        $internetShop = $internetShop->map(function ($internetShop) {
            return [
                'make' => $internetShop->make,
                'models' => $this->internetShop->where('dealer_id', auth()->user()->dealer_id)
                    ->where('make', $internetShop->make)
                    ->pluck('model')
                    ->values()
                    ->all(),
            ];
        })->unique()->values();

        return $internetShop;
    }
}
