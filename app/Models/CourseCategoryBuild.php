<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseCategoryBuild.
 *
 * @property int $id
 * @property int $course_build_id
 * @property int $category_build_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CategoryBuild $category_build
 * @property-read \App\Models\CourseBuild $course_build
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereCategoryBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereCourseBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategoryBuild whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseCategoryBuild extends Model
{
    protected $table = 'course_category_build';

    protected $fillable = [
        'course_build_id', 'category_build_id',
    ];

    protected $with = [
        'category_build',
    ];

    public function course_build()
    {
        return $this->belongsTo(CourseBuild::class);
    }

    public function category_build()
    {
        return $this->belongsTo(CategoryBuild::class);
    }
}
