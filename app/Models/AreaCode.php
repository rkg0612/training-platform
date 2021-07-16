<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AreaCode.
 *
 * @property int $id
 * @property int $state_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AreaCode whereValue($value)
 * @mixin \Eloquent
 */
class AreaCode extends Model
{
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
