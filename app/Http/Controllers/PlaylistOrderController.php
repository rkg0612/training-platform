<?php

namespace App\Http\Controllers;

use App\Services\LMS\PlaylistOrderService;
use Illuminate\Http\Request;

class PlaylistOrderController extends Controller
{
    public $playlistOrderService;

    public function __construct(PlaylistOrderService $playlistOrderService)
    {
        $this->playlistOrderService = $playlistOrderService;
    }

    public function update($id, Request $request)
    {
        return $this->playlistOrderService->update($id, $request->all());
    }
}
