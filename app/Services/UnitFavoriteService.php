<?php

namespace App\Services;

use App\Models\Unit;
use App\Models\UnitFavorite;

class UnitFavoriteService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index()
    {
        return Unit::whereHas('favorites', function ($query) {
            return $query->where('user_id', $this->user->id);
        })
            ->get();
    }

    public function store($request)
    {
        $unit = Unit::where('id', $request->unit_id)
            ->whereHas('module.category.course.dealers', function ($query) {
                $query->where('dealers.id', $this->user->dealer_id);
            })
            ->first();

        if (empty($unit)) {
            abort(404);
        }

        $unitFavorite = UnitFavorite::where('unit_id', $request->unit_id)
            ->where('user_id', $this->user->id)
            ->first();

        // if unit favorite exist, then delete it
        if (! empty($unitFavorite)) {
            $unitFavorite->delete();

            return;
        }

        $model = new UnitFavorite;
        $model->user_id = $this->user->id;
        $unit->favorites()->save($model);
    }

    public function destroy($id)
    {
        $unit = UnitFavorite::where('user_id', $this->user->id)
            ->where('unit_id', $id)
            ->first();

        if (empty($unit)) {
            abort(404);
        }

        $unit->delete();
    }
}
