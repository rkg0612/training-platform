<?php

namespace App\Models;

use App\Observers\ModuleBuildObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ModuleBuild.
 *
 * @property int $id
 * @property string $name
 * @property int $module_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CategoryModuleBuild|null $category_build
 * @property-read mixed $progress
 * @property-read \App\Models\Module $module
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ModuleSeries[] $series
 * @property-read int|null $series_count
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild newQuery()
 * @method static \Illuminate\Database\Query\Builder|ModuleBuild onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleBuild whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ModuleBuild withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ModuleBuild withoutTrashed()
 * @mixin \Eloquent
 */
class ModuleBuild extends Model
{
    use SoftDeletes;

    protected $table = 'module_build';

    protected $fillable = [
        'name', 'module_id',
    ];

    protected $appends = [
        'progress',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        parent::observe(new ModuleBuildObserver);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function series()
    {
        return $this->hasMany(ModuleSeries::class);
    }

    public function category_build()
    {
        return $this->hasOne(CategoryModuleBuild::class);
    }

    public function moduleProgress($user = null, $moduleId = null)
    {
        $module_id = isset($moduleId) ? $moduleId : $this->module_id;
        $user_units = UserUnit::with('user', 'unit')
            ->whereHas('unit.series.module_series.module_build', function ($query) use ($module_id) {
                $query->where('module_id', $module_id);
            })
            ->whereHas('user', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->whereNotNull('date_completed')
            ->get()
            ->unique('unit_id')
            ->count();

        if ($user_units == 0) {
            return 0;
        }

        if ($this->getTotalSeriesUnits() === 0) {
            return 0;
        }

        return ($user_units / $this->getTotalSeriesUnits()) * 100;
    }

    public function getTotalSeriesUnits()
    {
        return array_sum($this->series->pluck('totalUnits')->toArray());
    }

    public function getProgressAttribute()
    {
        return $this->moduleProgress(auth()->user());
    }

    public static function progress($userId, $moduleId)
    {
        return (new self)->moduleProgress(User::find($userId), $moduleId);
    }
}
