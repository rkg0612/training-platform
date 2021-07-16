<?php

namespace App\Http\Controllers;

use App\Services\GroupShopReportService;

class GroupShopReportController extends Controller
{
    public $groupShopService;

    public function __construct(GroupShopReportService $groupShopReportService)
    {
        $this->groupShopService = $groupShopReportService;
    }

    public function show($id)
    {
        return $this->groupShopService->getPreviewReport($id);
    }

    public function store($id)
    {
    }
}
