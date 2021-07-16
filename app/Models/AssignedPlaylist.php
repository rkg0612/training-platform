<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AssignedPlaylist.
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $user_id
 * @property int $assignee_id
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $shared_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedPlaylistUnit[] $assignedPlaylistUnit
 * @property-read int|null $assigned_playlist_unit_count
 * @property-read \App\Models\User $assignee
 * @property-read mixed $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \App\Models\Playlist $playlist
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereAssigneeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereSharedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignedPlaylist whereUserId($value)
 * @mixin \Eloquent
 */
class AssignedPlaylist extends Model
{
    public $appends = [
        'type',
    ];

    protected $dates = [
        'due_date',
    ];

    public function getTypeAttribute()
    {
        if ($this->due_date) {
            return 'assigned';
        }

        return 'shared';
    }

    public function assignedPlaylistUnit()
    {
        return $this->hasMany(AssignedPlaylistUnit::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }

    public function progress()
    {
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'assign_playlist_group');
    }
}
