<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FeaturedVideoRelatedUnit.
 *
 * @property int $id
 * @property int $featured_video_id
 * @property int $unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FeaturedVideo $featuredVideo
 * @property-read \App\Models\Unit $units
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit whereFeaturedVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeaturedVideoRelatedUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeaturedVideoRelatedUnit extends Model
{
    protected $fillable = [
        'featured_video_id', 'unit_id',
    ];

    public function featuredVideo()
    {
        return $this->belongsTo(FeaturedVideo::class);
    }

    public function units()
    {
        return $this->belongsTo(Unit::class);
    }
}
