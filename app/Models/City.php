<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\City.
 *
 * @property int $id
 * @property int $state_id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneShop[] $phoneShops
 * @property-read int|null $phone_shops_count
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereValue($value)
 * @mixin \Eloquent
 */
class City extends Model
{
    protected $fillable = [
        'state_id', 'value',
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }

    public function phoneShops()
    {
        return $this->hasMany(PhoneShop::class);
    }
}
