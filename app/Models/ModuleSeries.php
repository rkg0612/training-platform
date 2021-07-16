<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ModuleSeries.
 *
 * @property int $id
 * @property string $name
 * @property int $is_banner
 * @property int $module_build_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_units
 * @property-read \App\Models\ModuleBuild $module_build
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SeriesUnit[] $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereIsBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereModuleBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleSeries whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModuleSeries extends Model
{
    protected $fillable = [
        'name', 'is_banner',
    ];

    protected $appends = [
        'totalUnits',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($series) {
            $series->units()->forceDelete();
        });
    }

    public function module_build()
    {
        return $this->belongsTo(ModuleBuild::class);
    }

    public function units()
    {
        return $this->hasMany(SeriesUnit::class);
    }

    public function getTotalUnitsAttribute()
    {
        return $this->units->count();
    }
}
