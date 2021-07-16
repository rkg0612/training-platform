<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Services\SecretShopDataSummary\LeadSourceService;

class LeadSourceController
{
    public $leadSourceService;

    public function __construct(LeadSourceService $leadSourceService)
    {
        $this->leadSourceService = $leadSourceService;
    }

    public function index()
    {
        return $this->leadSourceService->index();
    }
}
