<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UnitForSidebar.
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
 * @property-read \App\Models\SeriesUnit|null $series
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar newQuery()
 * @method static \Illuminate\Database\Query\Builder|UnitForSidebar onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereCallGuideLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitForSidebar whereVideoDuration($value)
 * @method static \Illuminate\Database\Query\Builder|UnitForSidebar withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UnitForSidebar withoutTrashed()
 * @mixin \Eloquent
 */
class UnitForSidebar extends Model
{
    use SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name', 'module_id', 'thumbnail', 'description', 'video_duration', 'call_guide_link', 'content', 'quiz_id',
    ];

    public function series()
    {
        return $this->hasOne(SeriesUnit::class);
    }
}
