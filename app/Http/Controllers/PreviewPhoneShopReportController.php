<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneShop\PreviewReport;
use App\Services\PhoneShop\PreviewPhoneShopReportService;
use Illuminate\Http\Request;

class PreviewPhoneShopReportController extends Controller
{
    public $previewPhoneShopReportService;

    public function __construct(PreviewPhoneShopReportService $phoneShopReportService)
    {
        $this->previewPhoneShopReportService = $phoneShopReportService;
    }

    public function show(PreviewReport $request)
    {
        return $this->previewPhoneShopReportService->show($request->validated()['id']);
    }
}
