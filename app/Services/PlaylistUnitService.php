<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\Unit;
use Illuminate\Http\Request;

class PlaylistUnitService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index(Request $request)
    {
        return Unit::with(['module'])
            ->whereHas('module.category.course.dealers', function ($query) {
                $query->where('dealers.id', $this->user->dealer_id);
            })
            ->where(function ($query) use ($request) {
                if (! $request->has('filter')) {
                    return;
                }

                $query->where('name', 'like', "%{$request->input('filter')}%");
            })->get();
    }

    public function store($request)
    {
        $unit = Unit::with('module')
            ->where('id', $request->unit_id)
            ->whereHas('module.category.course.dealers', function ($query) {
                $query->where('dealers.id', $this->user->dealer_id);
            })
            ->first();

        if (empty($unit)) {
            abort(404);
        }

        foreach ($request->playlists as $playlist) {
            // skip if playlist is not owned by user
            if ($playlist['user_id'] != $this->user->id) {
                continue;
            }

            $model = Playlist::with(['units'])
                ->where('id', $playlist['id'])
                ->first();

            // skip if unit is already in the playlist
            if ($model->units->contains('id', $unit->id)) {
                continue;
            }

            $model->units()->save($unit);
        }

        return $unit;
    }
}
