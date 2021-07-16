<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompareDealerPerformanceRequest;
use App\Services\SecretShopDataSummary\CompareDealerPerformanceService;

class CompareDealerPerformanceController extends Controller
{
    public $compareDealerPerformanceService;

    public function __construct(CompareDealerPerformanceService $compareDealerPerformanceService)
    {
        $this->compareDealerPerformanceService = $compareDealerPerformanceService;
    }

    public function index(CompareDealerPerformanceRequest $request)
    {
        return $this->compareDealerPerformanceService->index($request->validated());
    }
}
