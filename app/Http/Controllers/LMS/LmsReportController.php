<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\LmsReportService;
use Illuminate\Http\Request;

class LmsReportController extends Controller
{
    private $lmsreportservice;

    public function __construct(LmsReportService $lmsreportservice)
    {
        $this->lmsreportservice = $lmsreportservice;
    }

    public function exportData(Request $request)
    {
        return $this->lmsreportservice->exportData($request);
    }

    public function index(Request $request)
    {
        return $this->lmsreportservice->index($request);
    }

    public function getUsers(Request $request)
    {
        return $this->lmsreportservice->getUsers($request);
    }

    public function getSpecificDealers(Request $request)
    {
        return $this->lmsreportservice->getSpecificDealers($request);
    }

    public function getModules(Request $request)
    {
        return $this->lmsreportservice->getModules($request);
    }

    public function getOutstandingModules(Request $request)
    {
        return $this->lmsreportservice->getOutstandingModules($request);
    }

    public function getOutstandingPlaylists(Request $request)
    {
        return $this->lmsreportservice->getOutstandingPlaylists($request);
    }

    public function getTeamProgress(Request $request)
    {
        return $this->lmsreportservice->getTeamProgress($request);
    }

    public function exportOverviewReport(Request $request)
    {
        return $this->lmsreportservice->exportOverviewReport($request);
    }

    public function getOverviewModuleHeaders(Request $request)
    {
        return $this->lmsreportservice->getOverviewModuleHeaders($request);
    }

    public function getOverviewReport(Request $request)
    {
        return $this->lmsreportservice->getOverviewReport($request);
    }
}
