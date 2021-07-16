<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\SpecificDealerService;

class SpecificDealerController extends Controller
{
    public $specificDealerService;

    public function __construct(SpecificDealerService $specificDealerService)
    {
        $this->specificDealerService = $specificDealerService;
    }

    public function index()
    {
        return $this->specificDealerService->index();
    }
}
