<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="TwitterConfig",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="consumer_key",
 *          description="consumer_key",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="consumer_key_secret",
 *          description="consumer_key_secret",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="access_token",
 *          description="access_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="access_token_secret",
 *          description="access_token_secret",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
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
