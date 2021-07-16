<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\UnitClientService;
use Illuminate\Http\Request;

class UnitClientController extends Controller
{
    public $unitClientService;

    public function __construct(UnitClientService $unitClientService)
    {
        $this->unitClientService = $unitClientService;
    }

    public function show($id)
    {
        return $this->unitClientService->show($id);
    }
}
