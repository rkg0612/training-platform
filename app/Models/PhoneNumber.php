<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\PhoneNumber.
 *
 * @property int $id
 * @property string|null $sid
 * @property int|null $state_id
 * @property string|null $area_codes
 * @property string $value
 * @property int $dealer_id
 * @property int|null $user_id
 * @property int $is_dealer
 * @property int|null $temporary_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $voice_mail_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $formatted_value
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneNumberCallLog[] $calls
 * @property-read int|null $calls_count
 * @property-read \App\Models\Dealer $dealer
 * @property-read mixed $category_value
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PhoneNumberSMSLog[] $sms
 * @property-read int|null $sms_count
 * @property-read \App\Models\State|null $state
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\VoiceMail|null $voiceMail
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber newQuery()
 * @method static \Illuminate\Database\Query\Builder|PhoneNumber onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereAreaCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereFormattedValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereIsDealer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereTemporaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhoneNumber whereVoiceMailId($value)
 * @method static \Illuminate\Database\Query\Builder|PhoneNumber withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PhoneNumber withoutTrashed()
 * @mixin \Eloquent
 */
class PhoneNumber extends Model
{
    use SoftDeletes;

    protected $table = 'phone_numbers';

    protected $appends = [
        'category_value',
    ];

    protected $fillable = [
        'state_id', 'area_codes', 'value', 'dealer_id', 'voice_mail_id', 'is_dealer', 'user_id', 'sid', 'formatted_value',
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function voiceMail()
    {
        return $this->belongsTo('App\Models\VoiceMail');
    }

    public function calls()
    {
        return $this->hasMany(PhoneNumberCallLog::class);
    }

    public function sms()
    {
        return $this->hasMany(PhoneNumberSMSLog::class);
    }

    public function internetShop()
    {
        return $this->hasOne(internetShop::class, 'lead_phone_number', 'value')
            ->withTrashed();
    }

    public function getCategoryValueAttribute()
    {
        return  $this->is_dealer ? 'For Dealer' : 'For Competitor';
    }
}
