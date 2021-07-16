<?php

namespace App\Http\Controllers;

use App\Services\ExportGroupShopReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportGroupShopController extends Controller
{
    private $exportGroupShopReportService;

    public function __construct(ExportGroupShopReportService $exportGroupShopReportService)
    {
        $this->exportGroupShopReportService = $exportGroupShopReportService;
    }

    public function index(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        return response()
            ->download(
                storage_path("app/group-shop-reports/$request->fileName"),
                $request->fileName)
            ->deleteFileAfterSend();
    }

    public function show($id)
    {
        return response($this->exportGroupShopReportService->createLogs($id));
    }
}
