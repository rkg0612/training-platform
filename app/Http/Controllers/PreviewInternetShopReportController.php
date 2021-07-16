<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternetShop\PreviewReport;
use App\Services\InternetShop\PreviewInternetShopReportService;
use Illuminate\Http\Request;

class PreviewInternetShopReportController extends Controller
{
    public $previewInternetShopReportService;

    public function __construct(PreviewInternetShopReportService $previewInternetShopReportService)
    {
        $this->previewInternetShopReportService = $previewInternetShopReportService;
    }

    public function show(PreviewReport $request)
    {
        return $this->previewInternetShopReportService->show($request->validated()['id']);
    }
}
