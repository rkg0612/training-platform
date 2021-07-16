<?php

namespace App\Http\Controllers;

use App\Services\VideoOfTheDay\FeaturedVideoRelatedUnitService;
use Illuminate\Http\Request;

class FeaturedVideoRelatedUnitController extends Controller
{
    public $featuredVideoRelatedUnitService;

    public function __construct(FeaturedVideoRelatedUnitService $featuredVideoRelatedUnitService)
    {
        $this->featuredVideoRelatedUnitService = $featuredVideoRelatedUnitService;
    }

    public function index(Request $request)
    {
        return $this->featuredVideoRelatedUnitService->index($request);
    }
}
