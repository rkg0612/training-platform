<?php

namespace App\Http\Controllers;

use App\Services\StateService;

class StateController extends Controller
{
    private $stateService;

    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
    }

    public function __invoke()
    {
        return $this->stateService->getStates();
    }
}
