<?php

namespace App\Models;

use App\SpecificDealerCompetitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\InternetShop.
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $is_dealer
 * @property int|null $dealer_id
 * @property int|null $specific_dealer_id
 * @property string|null $timezone
 * @property int|null $state_id
 * @property int|null $city_id
 * @property int $is_shop_new
 * @property string|null $lead_source
 * @property string|null $zipcode
 * @property string|null $source_link
 * @property string|null $vehicle_identification_number
 * @property string|null $make
 * @property string|null $model
 * @property string|null $lead_name
 * @property string|null $lead_email
 * @property string|null $lead_phone_number
 * @property string|null $salesperson_name
 * @property string|null $csm_name
 * @property string|null $call_guide_link
 * @property \Illuminate\Support\Carbon|null $start_at
 * @property string|null $shop_duration
 * @property string|null $verification_screenshot
 * @property string|null $verification_screenshot_fallback
 * @property string|null $call_first_received
 * @property string|null $call_response_time
 * @property int|null $call_attempts
 * @property string|null $sms_first_received
 * @property string|null $sms_response_time
 * @property int|null $sms_attempts
 * @property string|null $email_first_received
 * @property string|null $email_response_time
 * @property int|null $email_attempts
 * @property string|null $email_second_received
 * @property string|null $email_second_response_time
 * @property string|null $chat_first_received
 * @property string|null $chat_response_time
 * @property int|null $chat_attempts
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $report_content
 * @property int|null $competitor_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShopChatScreenshot[] $chatScreenshots
 * @property-read int|null $chat_screenshots_count
 * @property-read \App\Models\City|null $city
 * @property-read SpecificDealerCompetitor|null $competitor
 * @property-read \App\Models\Dealer|null $dealer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShopEmailScreenshot[] $emailScreenshots
 * @property-read int|null $email_screenshots_count
 * @property-read mixed $call_first_received_formatted
 * @property-read mixed $category
 * @property-read mixed $chat_first_received_formatted
 * @property-read mixed $email_first_received_formatted
 * @property-read mixed $email_second_received_formatted
 * @property-read mixed $sms_first_received_formatted
 * @property-read mixed $start_at_formatted
 * @property-read \App\Models\PhoneNumber|null $phoneNumber
 * @property-read \App\Models\User|null $postedBy
 * @property-read \App\Models\LeadSource|null $source
 * @property-read \App\Models\SpecificDealer|null $specificDealer
 * @property-read \App\Models\State|null $state
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop getRecordsBasedOnParameters($args)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop newQuery()
 * @method static \Illuminate\Database\Query\Builder|InternetShop onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCallAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCallFirstReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCallGuideLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCallResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereChatAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereChatFirstReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereChatResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCompetitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereCsmName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereEmailAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereEmailFirstReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereEmailResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereEmailSecondReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereEmailSecondResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereIsDealer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereIsShopNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereLeadEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereLeadName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereLeadPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereLeadSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereReportContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSalespersonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereShopDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSmsAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSmsFirstReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSmsResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSourceLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereSpecificDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereVehicleIdentificationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereVerificationScreenshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereVerificationScreenshotFallback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetShop whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|InternetShop withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InternetShop withoutTrashed()
 * @mixin \Eloquent
 */
class InternetShop extends Model
{
    use SoftDeletes;

    protected $appends = [
        'category',
        'call_first_received_formatted',
        'sms_first_received_formatted',
        'email_first_received_formatted',
        'email_second_received_formatted',
        'chat_first_received_formatted',
        'chat_first_received_formatted',
        'start_at_formatted',
    ];

    protected $dates = [
        'start_at',
    ];

    protected $fillable = [
        'is_dealer',
        'dealer_id',
        'timezone',
        'zipcode',
        'state_id',
        'city_id',
        'user_id',
        'is_shop_new',
        'source_link',
        'salesperson_name',
        'call_guide_link',
        'shop_duration',
        'lead_name',
        'lead_email',
        'lead_phone_number',
        'vehicle_identification_number',
        'make',
        'model',
        'call_response_time',
        'sms_response_time',
        'email_response_time',
        'email_second_response_time',
        'chat_response_time',
    ];

    public function chatScreenshots()
    {
        return $this->hasMany(InternetShopChatScreenshot::class);
    }

    public function emailScreenshots()
    {
        return $this->hasMany(InternetShopEmailScreenshot::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specificDealer()
    {
        return $this->belongsTo(SpecificDealer::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class, 'lead_phone_number', 'value')
            ->withTrashed();
    }

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source');
    }

    public function truecar_fields()
    {
        return $this->hasOne(TrueCarField::class);
    }

    public function getCategoryAttribute()
    {
        return $this->is_dealer ? 'For Dealer' : 'For Competitor';
    }

    public function scopeGetRecordsBasedOnParameters($query, $args)
    {
        $query = self::query();

        foreach ($args['params'] as $param => $value) {
            if (in_array($param, $args['ranges'])) {
                $query->whereBetween($param, $value);
            }
            if (is_array($value) && ! in_array($param, $args['ranges']) && count($value) <= 2) {
                $query->whereIn($param, $value);
            }
            if (is_array($value) && count($value) <= 1) {
                $query->where($param, $value);
            }
            if (! is_array($value)) {
                $query->where($param, $value);
            }
        }

        return $query;
    }

    public function competitor()
    {
        return $this->belongsTo(SpecificDealerCompetitor::class, 'competitor_id');
    }

    public function convertTimezone($value)
    {
        $vDate = Carbon::parse($value);
        switch ($this->timezone) {
            case 'CST':
                $vDate->tz = 'America/Chicago';
                break;
            case 'MST':
                $vDate->tz = 'America/Denver';
                break;
            case 'PST':
                $vDate->tz = 'America/Los_Angeles';
                break;
            case 'AST':
                $vDate->tz = 'Atlantic/Bermuda';
                break;
            case 'HST':
                $vDate->tz = 'Pacific/Honolulu';
                break;
            default:
                break;
        }

        return $vDate->format('m/d/Y h:i A');
    }

    /**
     * Mutators.
     */
    public function getStartAtFormattedAttribute()
    {
        if (null !== $this->start_at) {
            return $this->convertTimezone($this->start_at);
        }

        return $this->start_at;
    }

    public function getCallFirstReceivedFormattedAttribute()
    {
        if (null !== $this->call_first_received) {
            return $this->convertTimezone($this->call_first_received);
        }

        return $this->call_first_received;
    }

    public function getSmsFirstReceivedFormattedAttribute()
    {
        if (null !== $this->sms_first_received) {
            return $this->convertTimezone($this->sms_first_received);
        }

        return $this->sms_first_received;
    }

    public function getEmailFirstReceivedFormattedAttribute()
    {
        if (null !== $this->email_first_received) {
            return $this->convertTimezone($this->email_first_received);
        }

        return $this->email_first_received;
    }

    public function getEmailSecondReceivedFormattedAttribute()
    {
        if (null !== $this->email_second_received) {
            return $this->convertTimezone($this->email_second_received);
        }

        return $this->email_second_received;
    }

    public function getChatFirstReceivedFormattedAttribute()
    {
        if (null !== $this->chat_first_received) {
            return $this->convertTimezone($this->chat_first_received);
        }

        return $this->chat_first_received;
    }

    public function scopeOrderBySpecificDealerName($query, $order = 'asc')
    {
        return $query->join('specific_dealers', 'specific_dealers.id', '=', 'internet_shops.specific_dealer_id')
            ->orderBy(\DB::raw('specific_dealers.name IS NULL, specific_dealers.name'), $order)
            ->addSelect('internet_shops.*')
            ->groupBy('internet_shops.id');
    }

    public function scopeOrderByDealerName($query, $order = 'asc')
    {
        return $query->join('dealers', 'dealers.id', '=', 'internet_shops.dealer_id')
            ->orderBy('dealers.name', $order)
            ->select('internet_shops.*')
            ->groupBy('internet_shops.id');
    }

    public function scopeOrderByCompetitorName($query, $order = 'asc')
    {
        return $query->leftJoin('specific_dealer_competitors', 'specific_dealer_competitors.id', '=', 'internet_shops.competitor_id')
            ->orderBy(\DB::raw('specific_dealer_competitors.name IS NULL, specific_dealer_competitors.name'), $order)
            ->addSelect('internet_shops.*')
            ->groupBy('internet_shops.id');
    }
}
