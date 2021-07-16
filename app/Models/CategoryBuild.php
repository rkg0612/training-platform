<?php

namespace App\Models;

use App\Observers\CategoryBuildObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CategoryBuild.
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\CourseCategoryBuild|null $course_build
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CategoryModuleBuild[] $modules
 * @property-read int|null $modules_count
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild newQuery()
 * @method static \Illuminate\Database\Query\Builder|CategoryBuild onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryBuild whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CategoryBuild withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CategoryBuild withoutTrashed()
 * @mixin \Eloquent
 */
class CategoryBuild extends Model
{
    use SoftDeletes;

    protected $table = 'category_build';

    protected $fillable = [
        'name', 'category_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        parent::observe(new CategoryBuildObserver);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(CategoryModuleBuild::class);
    }

    public function course_build()
    {
        return $this->hasOne(CourseCategoryBuild::class);
    }
}
