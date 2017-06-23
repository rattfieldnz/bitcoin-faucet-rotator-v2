<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_name",
 *          description="user_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_name",
 *          description="first_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bitcoin_address",
 *          description="bitcoin_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_admin",
 *          description="is_admin",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      )
 * )
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use SoftDeletes;
    use Notifiable;
    use Sluggable;

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
        'user_name' => 'min:5|max:15|required|unique:users,user_name',
        'first_name' => 'required|min:1|max:50',
        'last_name' => 'required|min:1|max:50',
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
        return $this->belongsToMany(Faucet::class, 'referral_info')->withPivot('user_id', 'faucet_id', 'referral_code', 'deleted_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function twitterConfig()
    {
        return $this->hasOne(TwitterConfig::class);
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

    public function isAnAdmin()
    {
        if ($this->is_admin == true && $this->hasRole('owner')) {
            return true;
        }
        return false;
    }

    public function userName()
    {
        if ($this->hasRole('owner') && $this->is_admin == true) {
            return 'admin';
        } else {
            return $this->user_name;
        }
    }

    public function userSlug()
    {
        if ($this->hasRole('owner') && $this->is_admin == true) {
            return 'admin';
        } else {
            return $this->slug;
        }
    }

    public function userFirstName()
    {
        if ($this->hasRole('owner') && $this->is_admin == true) {
            return 'Admin';
        } else {
            return $this->first_name;
        }
    }

    public function userLastName()
    {
        if ($this->hasRole('owner') && $this->is_admin == true) {
            return 'Admin';
        } else {
            return $this->last_name;
        }
    }

    public function isDeleted()
    {
        if ($this->attributes['deleted_at']) {
            return true;
        }
        return false;
    }
}
