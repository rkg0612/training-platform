<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role.
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $friendly_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RoleUser[] $value
 * @property-read int|null $value_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereFriendlyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    const SUPER_ADMINISTRATOR = 'super-administrator';
    const ACCOUNT_MANAGER = 'account-manager';
    const SPECIFIC_DEALER_MANAGER = 'specific-dealer-manager';
    const SECRET_SHOPPER = 'secret-shopper';
    const SALESPERSON = 'salesperson';

    protected $fillable = [
        'name', 'friend', 'friendly_name',
    ];

    public function value()
    {
        return $this->hasMany('App\Models\RoleUser');
    }
}
