<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UnitTag.
 *
 * @property int $id
 * @property int $unit_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tag $tag
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnitTag extends Pivot
{
    protected $table = 'unit_tag';

    protected $fillable = [
        'unit_id', 'tag_id',
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }
}
