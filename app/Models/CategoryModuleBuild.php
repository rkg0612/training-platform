<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CategoryModuleBuild.
 *
 * @property int $id
 * @property int $category_build_id
 * @property int $module_build_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CategoryBuild $category_build
 * @property-read \App\Models\ModuleBuild $module_build
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereCategoryBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereModuleBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryModuleBuild whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryModuleBuild extends Model
{
    protected $table = 'category_module_build';

    protected $fillable = [
        'category_build_id', 'module_build_id',
    ];

    public function category_build()
    {
        return $this->belongsTo(CategoryBuild::class);
    }

    public function module_build()
    {
        return $this->belongsTo(ModuleBuild::class);
    }
}
