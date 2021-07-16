<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ModuleExcludeUser.
 *
 * @property int $id
 * @property int $user_id
 * @property int $module_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Module $module
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|ModuleExcludeUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleExcludeUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|ModuleExcludeUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ModuleExcludeUser withoutTrashed()
 * @mixin \Eloquent
 */
class ModuleExcludeUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id', 'user_id',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
