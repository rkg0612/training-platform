<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\Unit;

class PlaylistAddUnitService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function store($request)
    {
        $playlist = $this->getPlaylist($request);

        if (empty($playlist)) {
            abort(404);
        }

        foreach ($request->units as $unit) {
            $model = Unit::where('id', $unit)
                ->first();

            if (empty($model)) {
                continue;
            }

            // skip if unit is already in the playlist
            if ($playlist->units->contains('id', $unit)) {
                continue;
            }

            $playlist->units()->save($model);
        }

        $playlist = $this->getPlaylist($request);

        return $playlist;
    }

    public function getPlaylist($request)
    {
        return Playlist::with(['units.module', 'user'])
            ->where('id', $request->playlist_id)
            ->where('user_id', $this->user->id)
            ->first();
    }
}
