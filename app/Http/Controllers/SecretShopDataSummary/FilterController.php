<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\FilterService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(Request $request)
    {
        return $this->filterService->index($request->all());
    }
}
