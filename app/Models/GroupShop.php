<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GroupShop.
 *
 * @property int $id
 * @property string|null $guest_view_id
 * @property string $name
 * @property int|null $specific_dealer_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $dealer_id
 * @property-read \App\Models\Dealer|null $dealer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneShop[] $phoneShops
 * @property-read int|null $phone_shops_count
 * @property-read \App\Models\User $secretShopper
 * @property-read \App\Models\SpecificDealer|null $specificDealer
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupShop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop orderByDealerName($order = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereGuestViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereSpecificDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupShop whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|GroupShop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupShop withoutTrashed()
 * @mixin \Eloquent
 */
class GroupShop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'dealer_id', 'specific_dealer_id', 'user_id', 'guest_view_id', 'hide_dealer_name',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function specificDealer()
    {
        return $this->belongsTo(SpecificDealer::class);
    }

    public function internetShops()
    {
        return $this->belongsToMany(InternetShop::class);
    }

    public function phoneShops()
    {
        return $this->belongsToMany(PhoneShop::class);
    }

    public function secretShopper()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeOrderByDealerName($query, $order = 'asc')
    {
        return $query->join('dealers', 'dealers.id', '=', 'phone_shops.dealer_id')
            ->orderBy('dealers.name', $order)
            ->select('phone_shops.*')
            ->groupBy('phone_shops.id');
    }
}
