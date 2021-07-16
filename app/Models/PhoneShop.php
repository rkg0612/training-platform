<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PhoneShop.
 *
 * @property int $id
 * @property string|null $guest_view_id
 * @property int $city_id
 * @property int $specific_dealer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $dealer_id
 * @property int $state_id
 * @property int $shop_type
 * @property string $sales_person_name
 * @property string $lead_name
 * @property string $start_date
 * @property string $inbound_call_grade
 * @property int $secret_shopper_id
 * @property string|null $zip_code
 * @property-read \App\Models\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CompetitorRecording[] $competitorRecordings
 * @property-read int|null $competitor_recordings_count
 * @property-read \App\Models\Dealer $dealer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DealerRecording[] $dealerRecordings
 * @property-read int|null $dealer_recordings_count
 * @property-read \App\Models\User $secretShopper
 * @property-read \App\Models\SpecificDealer $specificDealer
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop newQuery()
 * @method static \Illuminate\Database\Query\Builder|PhoneShop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop orderByDealerName($order = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop orderBySecretShopperName($order = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop orderBySpecificDealerName($order = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereGuestViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereInboundCallGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereLeadName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereSalesPersonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereSecretShopperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereShopType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereSpecificDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneShop whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|PhoneShop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PhoneShop withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneShop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dealer_id', 'specific_dealer_id', 'state_id', 'city_id', 'shop_type', 'sales_person_name', 'lead_name', 'start_date', 'inbound_call_grade', 'secret_shopper_id', 'zip_code', 'guest_view_id',
    ];

    public $casts = [
        'start_date',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function specificDealer()
    {
        return $this->belongsTo(SpecificDealer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function dealerRecordings()
    {
        return $this->hasMany(DealerRecording::class);
    }

    public function competitorRecordings()
    {
        return $this->hasMany(CompetitorRecording::class);
    }

    public function secretShopper()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrderBySecretShopperName($query, $order = 'asc')
    {
        return $query->join('users', 'users.id', '=', 'phone_shops.secret_shopper_id')
            ->orderBy('users.name', $order)
            ->select('phone_shops.*')
            ->groupBy('phone_shops.id');
    }

    public function scopeOrderBySpecificDealerName($query, $order = 'asc')
    {
        return $query->join('specific_dealers', 'specific_dealers.id', '=', 'phone_shops.specific_dealer_id')
            ->orderBy('specific_dealers.name', $order)
            ->select('phone_shops.*')
            ->groupBy('phone_shops.id');
    }

    public function scopeOrderByDealerName($query, $order = 'asc')
    {
        return $query->join('dealers', 'dealers.id', '=', 'phone_shops.dealer_id')
            ->orderBy('dealers.name', $order)
            ->select('phone_shops.*')
            ->groupBy('phone_shops.id');
    }
}
