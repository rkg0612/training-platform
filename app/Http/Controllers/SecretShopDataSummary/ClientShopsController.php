<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\ClientShopsFetchDataService;
use App\Services\ClientShopsFilterService;
use Illuminate\Http\Request;

class ClientShopsController extends Controller
{
    private $shopsFilterService;
    private $shopsFetchData;

    public function __construct(ClientShopsFilterService $shopsFilterService, ClientShopsFetchDataService $shopsFetchData)
    {
        $this->shopsFilterService = $shopsFilterService;
        $this->shopsFetchData = $shopsFetchData;
    }

    public function fetchFilters(Request $request)
    {
        return $this->shopsFilterService->index($request);
    }

    public function fetchData(Request $request)
    {
        return $this->shopsFetchData->index($request);
    }

    public function exportData(Request $request)
    {
        return $this->shopsFetchData->exportData($request);
    }
}
