<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TwitterConfig
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $consumer_key
 * @property string $consumer_key_secret
 * @property string $access_token
 * @property string $access_token_secret
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TwitterConfig onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereAccessTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereConsumerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereConsumerKeySecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TwitterConfig whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TwitterConfig withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TwitterConfig withoutTrashed()
 * @mixin \Eloquent
 */
class TwitterConfig extends Model
{
    use SoftDeletes;

    public $table = 'twitter_config';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'consumer_key',
        'consumer_key_secret',
        'access_token',
        'access_token_secret',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'consumer_key' => 'string',
        'consumer_key_secret' => 'string',
        'access_token' => 'string',
        'access_token_secret' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'consumer_key' => 'max:255',
        'consumer_key_secret' => 'max:255',
        'access_token' => 'max:255',
        'access_token_secret' => 'max:255',
        'user_id' => 'required|integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
