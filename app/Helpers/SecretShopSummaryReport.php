<?php

namespace App\Helpers;

use App\Models\City;
use App\Models\InternetShop;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SecretShopSummaryReport
{
    private $internetShop;
    private $state;
    private $city;

    public function __construct(InternetShop $internetShop, State $state, City $city)
    {
        $this->internetShop = $internetShop;
        $this->city = $city;
        $this->state = $state;
    }

    public function computeTenPercent($count): int
    {
        $count = $count * .10;

        if ($count < 9) {
            return 1;
        }

        return ceil($count);
    }

    public function createParams($request, $pickColumns = []): array
    {
        $params = (object) [];

        $ranges = [
            'start_at',
        ];

        foreach ($request as $column => $value) {
            if ($value && ! count($pickColumns)) {
                $params->$column = $value;
            } elseif ($value && in_array($column, $pickColumns)) {
                $params->$column = $value;
            }
        }

        $params->dealer_id = auth()->user()->dealer_id;

        return [
            'params' => $params,
            'ranges' => $ranges,
        ];
    }

    public function getAverageAttempts($internetShop, $column, $isTop, $tenPercent): int
    {
        $result = $internetShop->pluck($column);
        $result = ! $isTop ? $result->sort() : $result->sort()->reverse();
        $result = $result->reject(function ($attempts) {
            return $attempts === 0;
        })->take($tenPercent)->sum();

        return intval(ceil($result / $tenPercent));
    }

    public function getAverageResponseTime($internetShop, $column, $isTop, $tenPercent): string
    {
        $result = $internetShop->pluck($column);
        $result = $isTop ? $result->sort() : $result->sort()->reverse();
        $result = $result->map(function ($time) {
            return strtotime($time);
        })->reject(function ($time) {
            // 1584316800 is the value of 00:00:00 in strtotime
            return $time === 1584316800;
        })->take($tenPercent)->sum();

        return date('H:i:s', $result / $tenPercent);
    }

    public function removeNull($request): array
    {
        return Collection::make($request)->reject(function ($request) {
            return $request === null;
        })->toArray();
    }
}
