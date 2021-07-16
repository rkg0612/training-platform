<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Services\SecretShopDataSummary\SalesPersonService;

class SalesPersonController
{
    public $salesPersonService;

    public function __construct(SalesPersonService $salesPersonService)
    {
        $this->salesPersonService = $salesPersonService;
    }

    public function index()
    {
        return $this->salesPersonService->index();
    }
}
