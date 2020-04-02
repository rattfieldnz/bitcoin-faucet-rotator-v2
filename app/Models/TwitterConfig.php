<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig newQuery()
 * @method static Builder|TwitterConfig onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereAccessTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereConsumerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereConsumerKeySecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwitterConfig whereUserId($value)
 * @method static Builder|TwitterConfig withTrashed()
 * @method static Builder|TwitterConfig withoutTrashed()
 * @mixin Model
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
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
