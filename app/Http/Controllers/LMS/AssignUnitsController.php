<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\UserUnit;
use Facades\App\Services\LMS\AssignUnitsService;
use Illuminate\Http\Request;

class AssignUnitsController extends Controller
{
    public function assignUnit(Request $request)
    {
        return AssignUnitsService::assignUnit($request);
    }

    public function assignModule(Request $request)
    {
        return AssignUnitsService::assignModule($request);
    }

    public function fetchUsersByDealer()
    {
        return AssignUnitsService::fetchUsersByDealer();
    }
}
