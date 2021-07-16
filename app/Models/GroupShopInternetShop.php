<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\GroupShopInternetShop.
 *
 * @property int|null $group_shop_id
 * @property int|null $internet_shop_id
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopInternetShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopInternetShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopInternetShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopInternetShop whereGroupShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopInternetShop whereInternetShopId($value)
 * @mixin \Eloquent
 */
class GroupShopInternetShop extends Pivot
{
    protected $table = 'group_shop_internet_shop';
}
