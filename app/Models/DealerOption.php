<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DealerOption.
 *
 * @property int $id
 * @property int|null $dealer_id
 * @property string $name
 * @property string|null $value
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DealerOption whereValue($value)
 * @mixin \Eloquent
 */
class DealerOption extends Model
{
    protected $table = 'dealer_options';

    protected $fillable = [
        'name', 'type', 'value',
    ];
}
