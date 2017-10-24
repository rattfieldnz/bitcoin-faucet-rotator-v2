<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialNetworks
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 */
class SocialNetworks extends Model
{
    use SoftDeletes;

    public $table = 'social_network_links';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'facebook_url',
        'twitter_url',
        'reddit_url',
        'google_plus_url',
        'youtube_url',
        'digg_url',
        'flickr_url',
        'instagram_url',
        'odnoklassniki_url',
        'pinterest_url',
        'stumbleupon_url',
        'tumblr_url',
        'vimeo_url',
        'vkontakte_url',
        'sinaweibo_url',
        'xing_url',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'facebook_url' => 'string',
        'twitter_url' => 'string',
        'reddit_url' => 'string',
        'google_plus_url' => 'string',
        'youtube_url' => 'string',
        'digg_url' => 'string',
        'flickr_url' => 'string',
        'instagram_url' => 'string',
        'odnoklassniki_url' => 'string',
        'pinterest_url' => 'string',
        'stumbleupon_url' => 'string',
        'tumblr_url' => 'string',
        'vimeo_url' => 'string',
        'vkontakte_url' => 'string',
        'sinaweibo_url' => 'string',
        'xing_url' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
