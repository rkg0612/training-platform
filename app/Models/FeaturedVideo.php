<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FeaturedVideo.
 *
 * @property int $id
 * @property string|null $title
 * @property string $url
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $relatedUnits
 * @property-read int|null $related_units_count
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo newQuery()
 * @method static \Illuminate\Database\Query\Builder|FeaturedVideo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideo whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|FeaturedVideo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FeaturedVideo withoutTrashed()
 * @mixin \Eloquent
 */
class FeaturedVideo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'url', 'start_at', 'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = Carbon::parse($value);
    }

    public function setEndAtAttribute($value)
    {
        $this->attributes['end_at'] = Carbon::parse($value);
    }

    public function relatedUnits()
    {
        return $this->belongsToMany(
            Unit::class,
            'featured_video_related_units'
            );
    }
}
