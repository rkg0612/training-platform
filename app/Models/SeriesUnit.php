<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SeriesUnit.
 *
 * @property int $id
 * @property int $module_series_id
 * @property int $unit_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ModuleSeries $module_series
 * @property-read \App\Models\Unit $unit
 * @property-read \App\Models\UnitForSidebar $unitForMenu
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereModuleSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeriesUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeriesUnit extends Model
{
    protected $with = [
        'unit',
    ];

    public function module_series()
    {
        return $this->belongsTo(ModuleSeries::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function unitForMenu()
    {
        return $this->belongsTo(UnitForSidebar::class, 'unit_id');
    }
}
