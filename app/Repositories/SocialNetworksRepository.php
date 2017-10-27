<?php

namespace App\Repositories;

use App\Models\SocialNetworks;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

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

    /**
     * Create a new set of social links.
     *
     * @param  array $data
     * @return SocialNetworks
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $socialLinksData = self::cleanInput($data);
        $socialLinks = parent::create($socialLinksData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($socialLinks, $socialLinksData);
        $socialLinks->save();
        return $this->parserResult($socialLinks);
    }

    /**
     * Update the specified set of social links.
     *
     * @param array $data
     * @param $id
     *
     * @return mixed
     */
    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $socialLinksData = self::cleanInput($data);
        $socialLinks = SocialNetworks::where('id', $id)->first();
        $updatedSocialLinks = $socialLinks->fill($socialLinksData);
        $this->skipPresenter($temporarySkipPresenter);
        $updatedSocialLinks = $this->updateRelations($updatedSocialLinks, $socialLinksData);
        $updatedSocialLinks->save();
        return $this->parserResult($updatedSocialLinks);
    }
    /**
     * Sanitize input / links data.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        return [
            'facebook_url' => !empty($data['facebook_url']) ? Purifier::clean($data['facebook_url'], 'generalFields') : null,
            'twitter_url' => !empty($data['twitter_url']) ? Purifier::clean($data['twitter_url'], 'generalFields'): null,
            'reddit_url' => !empty($data['reddit_url']) ? Purifier::clean($data['reddit_url'], 'generalFields'): null,
            'google_plus_url' => !empty($data['google_plus_url']) ? Purifier::clean($data['google_plus_url'], 'generalFields'): null,
            'youtube_url' => !empty($data['youtube_url']) ? Purifier::clean($data['youtube_url'], 'generalFields'): null,
            'tumblr_url' => !empty($data['tumblr_url']) ? Purifier::clean($data['tumblr_url'], 'generalFields'): null,
            'vimeo_url' => !empty($data['vimeo_url']) ? Purifier::clean($data['vimeo_url'], 'generalFields'): null,
            'vkontakte_url' => !empty($data['vkontakte_url']) ? Purifier::clean($data['vkontakte_url'], 'generalFields'): null,
            'sinaweibo_url' => !empty($data['sinaweibo_url']) ? Purifier::clean($data['sinaweibo_url'], 'generalFields'): null,
            'xing_url' => !empty($data['xing_url']) ? Purifier::clean($data['xing_url'], 'generalFields'): null,
            'user_id' => Purifier::clean($data['user_id'], 'generalFields'),
        ];
    }
}
