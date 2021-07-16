<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CategoryExcludeUser.
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|CategoryExcludeUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryExcludeUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|CategoryExcludeUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CategoryExcludeUser withoutTrashed()
 * @mixin \Eloquent
 */
class CategoryExcludeUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
