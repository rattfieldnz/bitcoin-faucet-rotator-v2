<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Notifications\ResetPasswordNotification;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;

/**
 * Class User
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 */
class User extends Authenticatable implements CanResetPassword
{
    use LaratrustUserTrait;
    use SoftDeletes;
    use Notifiable;
    use Sluggable;
    use CausesActivity;
    use HasApiTokens;
    use ResetPassword;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_name',
        'first_name',
        'last_name',
        'email',
        'password',
        'bitcoin_address',
        'is_admin',
        'remember_token',
        'last_login_at',
        'last_logout_at',
        'last_login_ip'
    ];

    //protected $hidden = ['password'];

    protected $guarded = ['id', 'password'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_name' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'bitcoin_address' => 'string',
        'is_admin' => 'boolean',
        'remember_token' => 'string',
        'last_login_at' => 'string',
        'last_login_ip' => 'last_login_ip',
        'last_logout_at',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * Password must have at least 1 upper and lower-case character,
     * at least 1 number, and at least 1 symbol.
     *
     * @var array
     */
    public static $rules = [
        'user_name' => 'min:5|max:15|required|valid_user_name|not_start_with_number|no_punctuation|unique:users,user_name',
        'first_name' => 'required|min:1|max:50|no_numbers|no_punctuation',
        'last_name' => 'required|min:1|max:50|no_numbers|no_punctuation',
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'confirmed',
            'min:10',
            'max:20',
            'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
        ],
        'bitcoin_address' => 'required|string|min:26|max:35|unique:users,bitcoin_address',
    ];

    protected static $logAttributes = ['user_name', 'first_name', 'last_name', 'email', 'bitcoin_address'];

    protected static $logOnlyDirty = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function adBlock()
    {
        return $this->hasOne(AdBlock::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function faucets()
    {
        return $this->belongsToMany(Faucet::class, 'referral_info')->withPivot('user_id', 'faucet_id', 'referral_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function twitterConfig()
    {
        return $this->hasOne(TwitterConfig::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialNetworkLinks()
    {
        return $this->hasOne(SocialNetworks::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'user_name'
            ]
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return bool
     */
    public function isAnAdmin()
    {
        if ($this->is_admin == true && $this->hasRole('owner')) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        if ($this->attributes['deleted_at']) {
            return true;
        }
        return false;
    }

    /**
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "User '" . $this->user_name . "' has {$eventName} their profile.";
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->attributes['first_name'] . ' ' .
            $this->attributes['last_name'];
    }

    private function excludeAdminNameRule()
    {
        return $this->user_name != Constants::ADMIN_SLUG ? 'valid_user_name|' : '';
    }
}
