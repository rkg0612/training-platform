<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LeadSource.
 *
 * @property int $id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereValue($value)
 * @mixin \Eloquent
 */
class LeadSource extends Model
{
    protected $fillable = [
        'value',
    ];

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }
}
