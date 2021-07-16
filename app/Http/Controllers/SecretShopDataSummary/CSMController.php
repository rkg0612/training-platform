<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\CSMService;

class CSMController extends Controller
{
    public $csmService;

    public function __construct(CSMService $csmService)
    {
        $this->csmService = $csmService;
    }

    public function index()
    {
        return $this->csmService->index();
    }
}
