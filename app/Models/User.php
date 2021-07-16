<?php

namespace App\Models;

use App\Notifications\AssignedModuleNotification;
use App\Notifications\AssignedPlaylistNotification;
use App\Notifications\MailResetPasswordNotification;
use App\Notifications\ShareUnitNotification;
use App\Scopes\StatusScope;
use Doctrine\DBAL\Events;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $profile_picture
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $dealer_id
 * @property string|null $phone_number
 * @property int|null $specific_dealer_id
 * @property int $is_active
 * @property string|null $temporary_dealer
 * @property string $status
 * @property string|null $job_title
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property int $sms_notification
 * @property int $mail_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $assignUnits
 * @property-read int|null $assign_units_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedModule[] $assignedModules
 * @property-read int|null $assigned_modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedPlaylist[] $assignedPlaylists
 * @property-read int|null $assigned_playlists_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $assignedUnits
 * @property-read int|null $assigned_units_count
 * @property-read \App\Models\Dealer|null $dealer
 * @property-read mixed $is_manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InternetShop[] $internetShops
 * @property-read int|null $internet_shops_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedModule[] $myAssignedModules
 * @property-read int|null $my_assigned_modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AssignedPlaylist[] $myAssignedPlaylists
 * @property-read int|null $my_assigned_playlists_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\SpecificDealer|null $specificDealer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Unit[] $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMailSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSmsNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSpecificDealerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTemporaryDealer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const UNDER_REVIEW = 'UNDER_REVIEW';
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
    const REJECTED = 'REJECTED';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope);
    }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'dealer_id', 'specific_dealer_id', 'status', 'profile_picture', 'job_title', 'last_login_at', 'sms_notification', 'mail_subscription',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'date',
    ];

    protected $appends = [
        'is_manager', 'is_truecar',
    ];

    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer');
    }

    public function specificDealer()
    {
        return $this->belongsTo('App\Models\SpecificDealer');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();
    }

    public function internetShops()
    {
        return $this->hasMany(InternetShop::class);
    }

    public function events()
    {
        return $this->hasMany(Events::class);
    }

    public function receivesBroadcastNotificationsOn()
    {
        return "user-notification-{$this->id}";
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, UserUnit::class)
            ->withPivot('due_date', 'date_completed');
    }

    public function assignedUnits()
    {
        return $this->belongsToMany(Unit::class, UserUnit::class)
            ->withPivot('due_date', 'date_completed')
            ->wherePivot('assigned_by', '!=', null);
    }

    public function assignedModules()
    {
        return $this->hasMany(AssignedModule::class, 'id', 'user_id');
    }

    public function myAssignedModules()
    {
        return $this->hasMany(AssignedModule::class, 'assignee_id', 'id');
    }

    public function myAssignedPlaylists()
    {
        return $this->hasMany(AssignedPlaylist::class, 'assignee_id', 'id');
    }

    public function assignUnits()
    {
        return $this->belongsToMany('\App\Models\Unit', 'user_units', 'assigned_by', 'id');
    }

    public function assignedPlaylists()
    {
        return $this->hasMany(AssignedPlaylist::class, 'user_id', 'id');
    }

    private function hasRole($arr = [])
    {
        return RoleUser::where('user_id', $this->id)->whereIn('role_id', $arr)->exists();
    }

    public function getIsManagerAttribute()
    {
        return $this->hasRole([1, 2]);
    }

    public function getIsTruecarAttribute()
    {
        return $this->dealer_id === 48;
    }

    public function isAdmin()
    {
        return in_array(Role::SUPER_ADMINISTRATOR, $this->roles->pluck('name')->all());
    }

    public function isAccountManager()
    {
        return in_array(Role::ACCOUNT_MANAGER, $this->roles->pluck('name')->all());
    }

    public function isSpecificDealerManager()
    {
        return in_array(Role::SPECIFIC_DEALER_MANAGER, $this->roles->pluck('name')->all());
    }

    public function getSmsNotificationAttribute($value)
    {
        return $value ? true : false;
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot('intro_video_watched');
    }

    public function getMailSubscriptionAttribute($value)
    {
        return $value ? true : false;
    }

    public function sendAssignModule($link, $message, $modules, $dueDate)
    {
        if ($this->mail_subscription) {
            $subject = 'LMS Assigned Module Notification';
            $this->notify((new AssignedModuleNotification($link, $message, $modules, $dueDate, $this->name, $subject, 'modules')));
        }

        $this->notify((new ShareUnitNotification($link, $message)));
    }

    public function sendAssignPlaylist($link, $message, $playlist, $dueDate)
    {
        if ($this->mail_subscription) {
            $subject = 'LMS Assigned Playlist Notification';
            $this->notify((new AssignedModuleNotification($link, $message, $playlist, $dueDate, $this->name, $subject, 'playlists')));
        }

        $this->notify((new ShareUnitNotification($link, $message)));
    }
}
