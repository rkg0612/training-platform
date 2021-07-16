<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlaylistUnit.
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $list_order
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit whereListOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlaylistUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlaylistUnit extends Model
{
    protected $table = 'playlist_unit';

    protected $fillable = [
        'playlist_id', 'unit_id', 'list_order',
    ];
}
