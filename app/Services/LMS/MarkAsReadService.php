<?php

namespace App\Services\LMS;

use App\Models\AssignedModule;
use App\Models\AssignedModuleUnit;
use App\Models\ModuleBuild;
use App\Models\UserUnit;

class MarkAsReadService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function store($request)
    {
        if ($request['type'] === 'assigned' || $request['type'] === 'shared') {
            // assigned playlist units
        }

        if ($request['type'] === null) {
        }

        return 100;
    }

    public function checkForUserUnit($unitId)
    {
        $userUnit = UserUnit::where('user_id', auth()->user()->id)
                ->where('unit_id', $unitId)
                ->orderBy('date_completed', 'asc')
                ->first();

        if ($userUnit) {
            return $userUnit->date_completed;
        }
    }

    public function checkForAssignedPlaylistUnit($unitId)
    {
    }
}
