<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\InternetShopChatScreenshot.
 *
 * @property int $id
 * @property int $internet_shop_id
 * @property string $value
 * @property string $fall_back
 * @property int|null $order_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InternetShop $internetShop
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot newQuery()
 * @method static \Illuminate\Database\Query\Builder|InternetShopChatScreenshot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereFallBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereInternetShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereOrderBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShopChatScreenshot whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|InternetShopChatScreenshot withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InternetShopChatScreenshot withoutTrashed()
 * @mixin \Eloquent
 */
class InternetShopChatScreenshot extends Model
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
