<?php

namespace App\Http\Controllers;

use App\Services\CityService;

class CityController extends Controller
{
    private $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function show($stateId)
    {
        return $this->cityService->show($stateId);
    }
}
