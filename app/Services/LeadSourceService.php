<?php

namespace App\Services;

use App\Models\LeadSource;

class LeadSourceService
{
    private $leadsource;

    public function __construct(LeadSource $leadsource)
    {
        $this->leadsource = $leadsource;
    }

    public function getLeadSource()
    {
        return response()->json($this->leadsource->all());
    }
}
