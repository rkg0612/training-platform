<?php

namespace App\Services\SecretShopDataSummary;

use App\Models\Dealer;
use App\Models\InternetShop;
use App\Models\SpecificDealer;

class CompareDealerPerformanceService
{
    public $internetShop;
    public $specificDealer;
    public $date;

    public function __construct(InternetShop $internetShop, SpecificDealer $specificDealer)
    {
        $this->internetShop = $internetShop;
        $this->specificDealer = $specificDealer;
    }

    public function index($request)
    {
        if (isset($request['date'])) {
            $this->date = $request['date'];
        }

        return [
            [
                $this->specificDealer->findOrFail($request['dealerOne'])->name,
                $this->createTotalAttempts('email_attempts', $request['dealerOne']),
                $this->createTotalAttempts('call_attempts', $request['dealerOne']),
                $this->createTotalAttempts('sms_attempts', $request['dealerOne']),
            ],
            [
                $this->specificDealer->findOrFail($request['dealerTwo'])->name,
                $this->createTotalAttempts('email_attempts', $request['dealerTwo']),
                $this->createTotalAttempts('call_attempts', $request['dealerTwo']),
                $this->createTotalAttempts('sms_attempts', $request['dealerTwo']),
            ],
        ];
    }

    public function createTotalAttempts($column, $specificDealerId)
    {
        if (isset($this->date)) {
            return $this->internetShop->whereBetween('start_at', $this->date)->where('specific_dealer_id', $specificDealerId)->pluck($column)->sum();
        }

        return $this->internetShop->where('specific_dealer_id', $specificDealerId)->pluck($column)->sum();
    }
}
