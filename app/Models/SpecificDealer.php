<?php

namespace App\Models;

use App\SpecificDealerCompetitor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SpecificDealer.
 *
 * @property int $id
 * @property string $name
 * @property int|null $dealer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|SpecificDealerCompetitor[] $competitors
 * @property-read int|null $competitors_count
 * @property-read \App\Models\Dealer|null $dealer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read int|null $managers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneShop[] $phoneShops
 * @property-read int|null $phone_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer findPerson($id, $name)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer newQuery()
 * @method static \Illuminate\Database\Query\Builder|SpecificDealer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer query()
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpecificDealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SpecificDealer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SpecificDealer withoutTrashed()
 * @mixin \Eloquent
 */
class SpecificDealer extends Model
{
    use SoftDeletes;

    protected $table = 'specific_dealers';
    protected $fillable = ['name', 'dealer_id'];

    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer', 'dealer_id');
    }

    public function phoneShops()
    {
        return $this->hasMany('App\Models\PhoneShop');
    }

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }

    public function managers()
    {
        return $this->hasMany(User::class, 'specific_dealer_id');
    }

    public function scopeFindPerson($query, $id, $name)
    {
        if (is_int($name)) {
            return $query->where('id', $name)->get();
        }

        return $query->where([
            ['name', 'LIKE', '%'.$name.'%'],
            ['dealer_id', '=', $id],
        ])->get();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'specific_dealer_id');
    }

    public function isVisibleTo($user)
    {
        return $user->isAdmin() || $this->id == $user->specific_dealer_id || $this->dealer_id == $user->dealer_id;
    }

    public function competitors()
    {
        return $this->hasMany(SpecificDealerCompetitor::class);
    }
}
