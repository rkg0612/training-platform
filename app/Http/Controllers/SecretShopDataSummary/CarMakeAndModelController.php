<?php

namespace App\Http\Controllers\SecretShopDataSummary;

use App\Http\Controllers\Controller;
use App\Services\SecretShopDataSummary\CarMakeAndModelService;

class CarMakeAndModelController extends Controller
{
    public $carMakeAndModelService;

    public function __construct(CarMakeAndModelService $carMakeAndModelService)
    {
        $this->carMakeAndModelService = $carMakeAndModelService;
    }

    public function index()
    {
        return $this->carMakeAndModelService->index();
    }
}
