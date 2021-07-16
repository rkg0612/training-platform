<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\State.
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AreaCode[] $areaCodes
 * @property-read int|null $area_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneShop[] $phoneShops
 * @property-read int|null $phone_shops_count
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class State extends Model
{
    protected $fillable = [
        'name', 'abbreviation', 'area_codes',
    ];

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }

    public function phoneShops()
    {
        return $this->hasMany(PhoneShop::class);
    }

    public function areaCodes()
    {
        return $this->hasMany(AreaCode::class);
    }
}
