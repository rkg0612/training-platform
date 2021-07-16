<?php

namespace App\Services\LMS;

use App\Models\PlaylistUnit;

class PlaylistOrderService
{
    public function update($playlistId, $units)
    {
        $units = collect($units);
        $units->each(function ($unit, $index) use ($playlistId) {
            PlaylistUnit::where('playlist_id', $playlistId)
                ->where('unit_id', $unit['id'])
                ->update([
                    'list_order' => $index,
                ]);
        });

        return response('success', 200);
    }
}
