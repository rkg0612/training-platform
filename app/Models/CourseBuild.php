<?php

namespace App\Models;

use App\Observers\CourseBuildObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CourseBuild.
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseCategoryBuild[] $category_builds
 * @property-read int|null $category_builds_count
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild newQuery()
 * @method static \Illuminate\Database\Query\Builder|CourseBuild onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseBuild whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CourseBuild withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CourseBuild withoutTrashed()
 * @mixin \Eloquent
 */
class CourseBuild extends Model
{
    use SoftDeletes;

    protected $table = 'course_build';

    protected $fillable = [
        'name', 'course_id',
    ];

    protected $with = [
        'category_builds', 'course',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        parent::observe(new CourseBuildObserver);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function category_builds()
    {
        return $this->hasMany(CourseCategoryBuild::class);
    }
}
