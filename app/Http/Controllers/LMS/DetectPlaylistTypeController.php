<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\LMS\MarkAsCompleted\DetectPlaylistTypeService;
use Illuminate\Http\Request;

class DetectPlaylistTypeController extends Controller
{
    public $detectPlaylistType;

    public function __construct(DetectPlaylistTypeService $detectPlaylistType)
    {
        $this->detectPlaylistType = $detectPlaylistType;
    }

    public function determine(Request $request)
    {
        return response()->json([
            $this->detectPlaylistType->scan($request->playlistId),
        ], 200);
    }
}
