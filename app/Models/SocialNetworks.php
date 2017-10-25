<?php

namespace App\Models;

use App\Models\User;
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
        'facebook_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,facebook_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)facebook\.com\/.+/i'
        ],
        'twitter_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,twitter_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)twitter\.com\/.+/i'
        ],
        'reddit_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,reddit_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)reddit\.com\/.+/i'
        ],
        'google_plus_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,google_plus_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)plus\.google\.com\/.+/i'
        ],
        'youtube_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,youtube_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)youtube\.com\/.+/i'
        ],
        'tumblr_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,tumblr_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)tumblr\.com\/.+/i'
        ],
        'vimeo_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,vimeo_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)vimeo\.com\/.+/i'
        ],
        'vkontakte_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,vkontakte_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)vkontakte\.com\/.+/i'
        ],
        'sinaweibo_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,sinaweibo_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)weibo\.com\/.+/i'
        ],
        'xing_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,xing_url',
            'max:255',
            'regex:/http(?:s):\/\/(?:www\.)xing\.com\/.+/i'
        ],
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
