<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\SecretShopDataSummaryService;
use Illuminate\Http\Request;

class DataSummaryController extends Controller
{
    public $secretShopDataSummaryService;

    public function __construct(SecretShopDataSummaryService $secretShopDataSummaryService)
    {
        $this->secretShopDataSummaryService = $secretShopDataSummaryService;
    }

    public function index()
    {
        return $this->secretShopDataSummaryService->show();
    }

    public function show(Request $request)
    {
        return $this->secretShopDataSummaryService->index($request->all());
    }
}
