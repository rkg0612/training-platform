<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\RelatedUnit\RelatedUnitService;
use Illuminate\Http\Request;

class RelatedUnitController extends Controller
{
    public $relatedUnitService;

    public function __construct(RelatedUnitService $relatedUnitService)
    {
        $this->relatedUnitService = $relatedUnitService;
    }

    public function index(Request $request)
    {
        return $this->relatedUnitService->index($request->all());
    }

    public function store(Request $request)
    {
        return $this->relatedUnitService->show($request->all());
    }
}
