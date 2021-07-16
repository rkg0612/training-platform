<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhoneNumberCallLog.
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
 * @property-read \App\Models\PhoneNumber|null $phoneNumber
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereCallSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog wherePhoneNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberCallLog whereValue($value)
 * @mixin \Eloquent
 */
class PhoneNumberCallLog extends Model
{
    protected $fillable = [
        'phone_number_id', 'value', 'start_at', 'from', 'call_sid',
    ];

    protected $appends = [
        'start_at_formatted',
        'created_at_formatted',
    ];

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class);
    }

    /**
     * Mutators.
     */
    public function getStartAtFormattedAttribute()
    {
        if (null !== $this->start_at) {
            if ($this->phoneNumber && $this->phoneNumber->internetShop) {
                return $this->phoneNumber->internetShop->convertTimezone($this->start_at);
            }
        }

        return $this->start_at;
    }

    public function getCreatedAtFormattedAttribute()
    {
        if (null !== $this->created_at) {
            if ($this->phoneNumber && $this->phoneNumber->internetShop) {
                return $this->phoneNumber->internetShop->convertTimezone($this->created_at);
            }
        }

        return $this->created_at;
    }
}
