<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignedPlaylistUnit.
 *
 * @property int $id
 * @property int $assigned_playlist_id
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon $date_completed
 * @property int $played
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereAssignedPlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereDateCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit wherePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylistUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssignedPlaylistUnit extends Model
{
    protected $dates = [
        'date_completed',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
