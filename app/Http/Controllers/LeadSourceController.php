<?php

namespace App\Http\Controllers;

use App\Services\LeadSourceService;

class LeadSourceController extends Controller
{
    private $leadSourceService;

    public function __construct(LeadSourceService $leadSourceService)
    {
        $this->leadSourceService = $leadSourceService;
    }

    public function __invoke()
    {
        return $this->leadSourceService->getLeadSource();
    }
}
