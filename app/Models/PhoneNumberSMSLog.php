<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PhoneNumberSMSLog.
 *
 * @property int $id
 * @property int|null $phone_number_id
 * @property string|null $value
 * @property string|null $from
 * @property string|null $to
 * @property string $start_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sms_message_sid
 * @property-read \App\Models\PhoneNumber|null $phoneNumber
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog wherePhoneNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereSmsMessageSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumberSMSLog whereValue($value)
 * @mixin \Eloquent
 */
class PhoneNumberSMSLog extends Model
{
    protected $table = 'phone_number_sms_logs';

    protected $appends = [
        'start_at_formatted',
    ];

    protected $fillable = [
        'phone_number_id', 'value', 'start_at', 'from', 'sms_message_sid', 'to',
    ];

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

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class, 'phone_number_id');
    }

    public function medias()
    {
        return $this->hasMany(SmsMedia::class, 'sms_id');
    }
}
