<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\ModuleBuildService;
use App\Services\LMS\ModuleService;
use Illuminate\Http\Request;

class ModuleClientController extends Controller
{
    public $moduleBuildService;

    public function __construct(ModuleBuildService $moduleBuildService)
    {
        $this->moduleBuildService = $moduleBuildService;
    }

    public function show($id)
    {
        return $this->moduleBuildService->show($id);
    }
}
