<?php

namespace App\Repositories;

use App\Models\SocialNetworks;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SocialNetworksRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 * @version October 24, 2017, 10:35 pm NZDT
 *
 * @method SocialNetworks findWithoutFail($id, $columns = ['*'])
 * @method SocialNetworks find($id, $columns = ['*'])
 * @method SocialNetworks first($columns = ['*'])
*/
class SocialNetworksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Configure the Model
     **/
    public function model()
    {
        return SocialNetworks::class;
    }
}
