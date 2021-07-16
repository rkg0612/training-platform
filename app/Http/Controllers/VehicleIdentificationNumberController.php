<?php

namespace App\Http\Controllers;

use App\Services\VehicleIdentificationNumberService;
use Illuminate\Http\Request;

class VehicleIdentificationNumberController extends Controller
{
    private $vehicleIdentificationNumberService;

    public function __construct(VehicleIdentificationNumberService $vehicleIdentificationNumberService)
    {
        $this->vehicleIdentificationNumberService = $vehicleIdentificationNumberService;
    }

    public function __invoke(Request $request)
    {
        return $this->vehicleIdentificationNumberService->get($request->vin);
    }
}
