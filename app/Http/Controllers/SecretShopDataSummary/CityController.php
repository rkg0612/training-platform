<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\CityService;

class CityController extends Controller
{
    public $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index()
    {
        return $this->cityService->index();
    }
}
