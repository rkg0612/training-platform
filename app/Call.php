<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Call.
 *
 * @property int $id
 * @property int|null $phone_number_id
 * @property string|null $value
 * @property string|null $from
 * @property string|null $start_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $call_sid
 * @method static \Illuminate\Database\Eloquent\Builder|Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call query()
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereCallSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call wherePhoneNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereValue($value)
 * @mixin \Eloquent
 */
class Call extends Model
{
    protected $table = 'phone_number_call_logs';
}
