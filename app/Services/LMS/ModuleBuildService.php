<?php

namespace App\Services\LMS;

use App\Helpers\LMSRestrictUser;
use App\Models\ModuleBuild;

class ModuleBuildService
{
    public function show($id)
    {
        if (LMSRestrictUser::make(auth()->user()->id, $id, 'module')->isContentNotAccessible()) {
            abort(404);
        }

        return ModuleBuild::with('module', 'module.myAssigned', 'series')
            ->where('module_id', $id)
            ->first();
    }
}
