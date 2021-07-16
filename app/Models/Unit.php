<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Unit.
 *
 * @property int $id
 * @property int|null $module_id
 * @property string|null $thumbnail
 * @property string|null $description
 * @property string|null $video_duration
 * @property string|null $call_guide_link
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int|null $quiz_id
 * @property-read \App\Models\User|null $assignedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UnitFavorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $due_date
 * @property-read mixed $is_completed
 * @property-read mixed $video_url
 * @property-read \App\Models\Module|null $module
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Playlist[] $playlists
 * @property-read int|null $playlists_count
 * @property-read \App\Models\Quiz|null $quiz
 * @property-read \App\Models\SeriesUnit|null $series
 * @property-read \App\Models\User|null $sharedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Query\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCallGuideLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereVideoDuration($value)
 * @method static \Illuminate\Database\Query\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Unit withoutTrashed()
 * @mixin \Eloquent
 */
class Unit extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'name', 'module_id', 'thumbnail', 'description', 'video_duration', 'call_guide_link', 'content', 'quiz_id',
    ];

    protected $with = [
        'tags', 'module', 'assignedBy', 'sharedBy',
    ];

    protected $appends = [
        'video_url', 'is_completed', 'due_date',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, UnitTag::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function series()
    {
        return $this->hasOne(SeriesUnit::class);
    }

    public function getVideoUrlAttribute()
    {
        $url = $this->content;

        return $url;
    }

    public function getIsCompletedAttribute()
    {
        $user = auth()->user();

        if (is_null($user)) {
            return false;
        }

        $result = UserUnit::where('unit_id', $this->id)
                          ->where('user_id', auth()->user()->id)
                          ->whereNotNull('date_completed')
                          ->count();

        return $result > 0;
    }

    public function getDueDateAttribute()
    {
        $user = auth()->user();

        if (is_null($user)) {
            return false;
        }

        $result = UserUnit::where('unit_id', $this->id)
                          ->where('user_id', $user->id)
                          ->whereNotNull('due_date')
                          ->orderBy('due_date')
                          ->get()
                          ->first();

        return $result ? $result->due_date : null;
    }

    public function favorites()
    {
        return $this->hasMany(UnitFavorite::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, UserUnit::class)
            ->withPivot([
                'due_date',
                'date_completed',
                'played',
            ]);
    }

    public function assignedBy()
    {
        return $this->hasOneThrough(
            User::class,
            UserUnit::class,
            'unit_id',
            'id',
            'id',
            'assigned_by'
        );
    }

    public function sharedBy()
    {
        return $this->hasOneThrough(
            User::class,
            UserUnit::class,
            'unit_id',
            'id',
            'id',
            'shared_by'
        );
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function searchableAs()
    {
        return env('APP_ENV').'_units';
    }
}
