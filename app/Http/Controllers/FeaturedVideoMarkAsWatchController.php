<?php

namespace App\Http\Controllers;

use App\Services\VideoOfTheDay\FeaturedVideoMarkAsWatchService;
use Illuminate\Http\Request;

class FeaturedVideoMarkAsWatchController extends Controller
{
    public $featuredVideoMarkAsWatchService;

    public function __construct(FeaturedVideoMarkAsWatchService $featuredVideoMarkAsWatchService)
    {
        $this->featuredVideoMarkAsWatchService = $featuredVideoMarkAsWatchService;
    }

    public function show($featuredVideoId)
    {
        return response()->json([
            'watched' => $this->featuredVideoMarkAsWatchService->isFeaturedVideoWatched($featuredVideoId),
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'watched' => $this->featuredVideoMarkAsWatchService->markAsWatched($request->featuredVideoId),
        ]);
    }

    public function playedVideo(Request $request)
    {
        return response()->json([
            'played' => $this->featuredVideoMarkAsWatchService->playedVideo($request->featuredVideoId),
        ]);
    }
}
