<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\InternetShopEmailScreenshot.
 *
 * @property int $id
 * @property int|null $internet_shop_id
 * @property string $value
 * @property string $fall_back
 * @property int|null $order_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternetShop|null $internetShop
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot newQuery()
 * @method static \Illuminate\Database\Query\Builder|InternetShopEmailScreenshot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereFallBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereInternetShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereOrderBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopEmailScreenshot whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|InternetShopEmailScreenshot withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InternetShopEmailScreenshot withoutTrashed()
 * @mixin \Eloquent
 */
class InternetShopEmailScreenshot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'internet_shop_id', 'value',
    ];

    public function internetShop()
    {
        return $this->belongsTo(InternetShop::class);
    }
}
