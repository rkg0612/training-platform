<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CategoryCourse.
 *
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryCourse newQuery()
 * @method static \Illuminate\Database\Query\Builder|CategoryCourse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryCourse query()
 * @method static \Illuminate\Database\Query\Builder|CategoryCourse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CategoryCourse withoutTrashed()
 * @mixin \Eloquent
 */
class CategoryCourse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
