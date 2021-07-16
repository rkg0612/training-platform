<?php

namespace App\Http\Controllers;

use App\Services\SpecificDealerService;
use Illuminate\Http\Request;

class SpecificDealerController extends Controller
{
    private $specificDealerService;

    public function __construct(SpecificDealerService $specificDealerService)
    {
        $this->specificDealerService = $specificDealerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->specificDealerService->index();
    }
}
