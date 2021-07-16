<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Module.
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $thumbnail
 * @property string|null $description
 * @property string|null $call_guide_link
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedModule[] $assigned
 * @property-read int|null $assigned_count
 * @property-read \App\Models\ModuleBuild|null $build
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleExcludeUser[] $excludedUsers
 * @property-read int|null $excluded_users_count
 * @property-read mixed $intro_video_played
 * @property-read mixed $intro_video_watched
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property-read int|null $units_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Query\Builder|Module onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCallGuideLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Module withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Module withoutTrashed()
 * @mixin \Eloquent
 */
class Module extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'name', 'thumbnail', 'description', 'call_guide_link', 'category_id',
    ];

    protected $with = [
        'category',
    ];

    protected $appends = [
        'intro_video_watched', 'intro_video_played',
    ];

    public function unitProgress(User $user)
    {
        return $this->units()->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id)->whereNotNull('date_completed');
        })->get();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function excludedUsers()
    {
        return $this->hasMany(ModuleExcludeUser::class);
    }

    public function build()
    {
        return $this->hasOne(ModuleBuild::class);
    }

    public function myAssigned()
    {
        return $this->hasMany(AssignedModule::class)
           ->where('assignee_id', auth()->user()->id)
            ->orderBy('due_date', 'desc');
    }

    public function assigned()
    {
        return $this->hasMany(AssignedModule::class)
            ->orderBy('due_date', 'desc');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['intro_video_watched', 'played']);
    }

    // only good for the LMS since current user is checked
    public function getIntroVideoWatchedAttribute()
    {
        $currentUserId = auth()->id();
        $result = $this->users()->where('user_id', $currentUserId)->first();
        if (null !== $result) {
            return $result->pivot->intro_video_watched;
        }

        return false;
    }

    // only good for the LMS since current user is checked
    public function getIntroVideoPlayedAttribute()
    {
        $currentUserId = auth()->id();
        $result = $this->users()->where('user_id', $currentUserId)->first();
        if (null !== $result) {
            return $result->pivot->played;
        }

        return false;
    }

    public function searchableAs()
    {
        return env('APP_ENV').'_modules';
    }
}
