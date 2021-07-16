<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserWatchedFeaturedVideo.
 *
 * @property int $id
 * @property int $user_id
 * @property int $featured_video_id
 * @property bool $watched
 * @property bool $played
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FeaturedVideo $featuredVideo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereFeaturedVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo wherePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchedFeaturedVideo whereWatched($value)
 * @mixin \Eloquent
 */
class UserWatchedFeaturedVideo extends Model
{
    protected $fillable = [
        'user_id', 'featured_video_id', 'watched', 'played',
    ];

    protected $casts = [
        'watched'   =>  'boolean',
        'played'    =>  'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function featuredVideo()
    {
        return $this->belongsTo(FeaturedVideo::class);
    }
}
