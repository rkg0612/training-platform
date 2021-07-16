<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Dealer.
 *
 * @property int $id
 * @property string $name
 * @property string $website
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_automotive
 * @property int $is_active
 * @property int|null $temporary_dealer_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $lms_service
 * @property int $secretshop_service
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DealerOption[] $options
 * @property-read int|null $options_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneNumber[] $phoneNumbers
 * @property-read int|null $phone_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneShop[] $phoneShops
 * @property-read int|null $phone_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SpecificDealer[] $specificDealers
 * @property-read int|null $specific_dealers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer newQuery()
 * @method static \Illuminate\Database\Query\Builder|Dealer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereIsAutomotive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereLmsService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereSecretshopService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereTemporaryDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dealer whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|Dealer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Dealer withoutTrashed()
 * @mixin \Eloquent
 */
class Dealer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'website', 'address', 'is_automotive', 'lms_service', 'secretshop_service',
    ];

    public function phoneNumbers()
    {
        return $this->hasMany('App\Models\PhoneNumber');
    }

    public function specificDealers()
    {
        return $this->hasMany('App\Models\SpecificDealer');
    }

    public function options()
    {
        return $this->hasMany('App\Models\DealerOption');
    }

    public function phoneShops()
    {
        return $this->hasMany(PhoneShop::class);
    }

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // LMS Management

    public function courses()
    {
        return $this->belongsToMany(Course::class, CourseDealer::class);
    }

    public function isVisibleTo($user)
    {
        return $user->isAdmin() || $this->id == $user->dealer_id;
    }
}
