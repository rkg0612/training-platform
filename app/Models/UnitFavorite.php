<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UnitFavorite.
 *
 * @property int $id
 * @property int $user_id
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $unit
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitFavorite whereUserId($value)
 * @mixin \Eloquent
 */
class UnitFavorite extends Model
{
    public function unit()
    {
        return $this->belongsTo(User::class);
    }
}
