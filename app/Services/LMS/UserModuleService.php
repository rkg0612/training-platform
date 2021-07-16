<?php

namespace App\Services\LMS;

use App\Models\AssignedPlaylistUnit;
use App\Models\Module;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnit;
use Carbon\Carbon;

class UserModuleService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function setPlayedModuleVideo($request)
    {
        $module = Module::findOrFail($request->module_id);
        if (! $this->user->modules->contains($module->id)) {
            $this->user->modules()->save($module, [
                'played' => true,
                'created_at'    =>  \Carbon\Carbon::now(),
            ]);
        }

        return response()->json([
            'success'   =>  true,
        ], 200);
    }
}
