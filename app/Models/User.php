<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Notifications\ResetPasswordNotification;
use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;

/**
 * Class User
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $user_name
 * @property string $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string $password
 * @property string $bitcoin_address
 * @property bool $is_admin
 * @property string|null $remember_token
 * @property string|null $last_login_at
 * @property mixed|null $last_login_ip
 * @property string|null $last_logout_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $slug
 * @property Carbon|null $deleted_at
 * @property-read Collection|Activity[] $actions
 * @property-read int|null $actions_count
 * @property-read AdBlock $adBlock
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Faucet[] $faucets
 * @property-read int|null $faucets_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read SocialNetworks $socialNetworkLinks
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read TwitterConfig $twitterConfig
 * @method static \Illuminate\Database\Eloquent\Builder|User findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|User orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBitcoinAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserName($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @mixin Eloquent
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
        'last_logout_at' => 'string',
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
            'regex:/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]+$/'
        ],
        'password_confirmation' => 'sometimes|required_with:password|same:password',
        'bitcoin_address' => 'required|string|min:26|max:35|unique:users,bitcoin_address',
    ];

    protected static $logAttributes = ['user_name', 'first_name', 'last_name', 'email', 'bitcoin_address'];

    protected static $logOnlyDirty = true;

    /**
     * @return HasOne
     **/
    public function adBlock()
    {
        return $this->hasOne(AdBlock::class);
    }

    /**
     * @return BelongsToMany
     **/
    public function faucets()
    {
        return $this->belongsToMany(Faucet::class, 'referral_info')->withPivot('user_id', 'faucet_id', 'referral_code');
    }

    /**
     * @return HasOne
     **/
    public function twitterConfig()
    {
        return $this->hasOne(TwitterConfig::class);
    }

    /**
     * @return HasOne
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
