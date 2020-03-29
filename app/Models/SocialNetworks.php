<?php

namespace App\Models;

use App\Models\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialNetworks
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $reddit_url
 * @property string|null $google_plus_url
 * @property string|null $youtube_url
 * @property string|null $tumblr_url
 * @property string|null $vimeo_url
 * @property string|null $vkontakte_url
 * @property string|null $sinaweibo_url
 * @property string|null $xing_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNetworks onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereGooglePlusUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereRedditUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereSinaweiboUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereTumblrUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereVimeoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereVkontakteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereXingUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SocialNetworks whereYoutubeUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNetworks withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SocialNetworks withoutTrashed()
 * @mixin \Eloquent
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
            'regex:/http(s)?:\/\/(www\.)?facebook\.com\/.+/i',
            'nullable'
        ],
        'twitter_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,twitter_url',
            'max:255',
            'regex:/http(s)?:\/\/(www\.)?twitter\.com\/.+/i',
            'nullable'
        ],
        'reddit_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,reddit_url',
            'max:255',
            'regex:/http(s)?:\/\/(www\.)?reddit\.com\/.+/i',
            'nullable'
        ],
        'google_plus_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,google_plus_url',
            'max:255',
            'regex:/http(s)?:\/\/(www\.)?plus\.google\.com\/.+/i',
            'nullable'
        ],
        'youtube_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,youtube_url',
            'max:255',
            'regex:/http(s)?:\/\/(www\.)?youtube\.com\/.+/i',
            'nullable'
        ],
        'tumblr_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,tumblr_url',
            'max:255',
            'regex:/http(s)?:\/\/(www)|(.*)tumblr\.com/i',
            'nullable'
        ],
        'vimeo_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,vimeo_url',
            'max:255',
            'regex:/http(s)?:\/\/(www\.)?vimeo\.com\/.+/i',
            'nullable'
        ],
        'vkontakte_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,vkontakte_url',
            'max:255',
            'regex:/http(s):\/\/(www\.)?vk\.com\/.+/i',
            'nullable'
        ],
        'sinaweibo_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,sinaweibo_url',
            'max:255',
            'regex:/http(s):\/\/(www\.)?weibo\.com\/.+/i',
            'nullable'
        ],
        'xing_url' => [
            'sometimes',
            'url',
            'active_url',
            'unique:social_network_links,xing_url',
            'max:255',
            'regex:/http(s):\/\/(www\.)?xing\.com\/.+/i',
            'nullable'
        ],
        'user_id' => [
            'required',
            'integer',
            'unique:social_network_links,user_id'
        ]
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
