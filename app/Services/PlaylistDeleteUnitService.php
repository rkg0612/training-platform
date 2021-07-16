<?php

namespace App\Services;

use App\Models\Playlist;

class PlaylistDeleteUnitService
{
    public function store($request)
    {
        $playlist = Playlist::with('units')
            ->where('id', $request->playlist_id)
            ->first();

        foreach ($request->units as $unit) {
            $playlist->units()->detach($unit['id']);
        }
    }
}
