<?php

namespace App\Services\SecretShopDataSummary;

use App\Helpers\SecretShopSummaryReport;
use App\Models\InternetShop;

class FilterService
{
    public $internetShop;
    private $secretShopHelper;

    public function __construct(InternetShop $internetShop, SecretShopSummaryReport $secretShopHelper)
    {
        $this->internetShop = $internetShop;
        $this->secretShopHelper = $secretShopHelper;
    }

    public function index($params)
    {
        if (isset($params['dealerPerformance'])) {
            $internetShop = $this->fetchSpecificDealerIdOnly($params);
            if (count($internetShop)) {
                return $internetShop;
            }
            abort(404, 'No record found');
        }

        return $this->fetchResult($params)
            ->select(['state_id', 'city_id', 'specific_dealer_id', 'is_shop_new', 'salesperson_name', 'csm_name', 'make', 'model', 'lead_source'])
            ->get();
    }

    public function fetchResult($params)
    {
        return $this->internetShop->getRecordsBasedOnParameters($this->secretShopHelper->createParams($params));
    }

    public function fetchSpecificDealerIdOnly($params)
    {
        unset($params['dealerPerformance']);

        return $this->fetchResult($params)
            ->select(['specific_dealer_id'])
            ->get()
            ->map(function ($internetShop) {
                return $internetShop->specific_dealer_id;
            })
            ->unique()
            ->values();
    }
}
