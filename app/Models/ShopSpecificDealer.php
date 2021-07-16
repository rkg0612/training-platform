<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\ShopSpecificDealer.
 *
 * @property int $id
 * @property string $name
 * @property int|null $dealer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Dealer|null $dealer
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer findPerson($id, $name)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer newQuery()
 * @method static \Illuminate\Database\Query\Builder|ShopSpecificDealer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopSpecificDealer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ShopSpecificDealer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ShopSpecificDealer withoutTrashed()
 * @mixin \Eloquent
 */
class ShopSpecificDealer extends Model
{
    use SoftDeletes;

    protected $table = 'shop_specific_dealers';
    protected $fillable = ['name', 'dealer_id'];

    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer', 'dealer_id');
    }

    public function scopeFindPerson($query, $id, $name)
    {
        return $query->where([
            ['name', 'LIKE', '%'.$name.'%'],
            ['dealer_id', '=', $id],
        ])->get();
    }
}
