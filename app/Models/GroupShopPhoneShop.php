<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\GroupShopPhoneShop.
 *
 * @property int|null $group_shop_id
 * @property int|null $phone_shop_id
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopPhoneShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopPhoneShop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopPhoneShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopPhoneShop whereGroupShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShopPhoneShop wherePhoneShopId($value)
 * @mixin \Eloquent
 */
class GroupShopPhoneShop extends Pivot
{
    protected $table = 'group_shop_phone_shop';
}
