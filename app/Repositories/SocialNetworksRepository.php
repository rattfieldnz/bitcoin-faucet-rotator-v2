<?php

namespace App\Repositories;

use App\Models\SocialNetworks;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

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
class SocialNetworksRepository extends Repository
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
     * @param array $data
     * @return SocialNetworks
     * @throws ValidatorException
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
            'facebook_url' => !empty($data['facebook_url']) ? Purify::clean($data['facebook_url'], self::$generalFieldsConfig) : null,
            'twitter_url' => !empty($data['twitter_url']) ? Purify::clean($data['twitter_url'], self::$generalFieldsConfig): null,
            'reddit_url' => !empty($data['reddit_url']) ? Purify::clean($data['reddit_url'], self::$generalFieldsConfig): null,
            'google_plus_url' => !empty($data['google_plus_url']) ? Purify::clean($data['google_plus_url'], self::$generalFieldsConfig): null,
            'youtube_url' => !empty($data['youtube_url']) ? Purify::clean($data['youtube_url'], self::$generalFieldsConfig): null,
            'tumblr_url' => !empty($data['tumblr_url']) ? Purify::clean($data['tumblr_url'], self::$generalFieldsConfig): null,
            'vimeo_url' => !empty($data['vimeo_url']) ? Purify::clean($data['vimeo_url'], self::$generalFieldsConfig): null,
            'vkontakte_url' => !empty($data['vkontakte_url']) ? Purify::clean($data['vkontakte_url'], self::$generalFieldsConfig): null,
            'sinaweibo_url' => !empty($data['sinaweibo_url']) ? Purify::clean($data['sinaweibo_url'], self::$generalFieldsConfig): null,
            'xing_url' => !empty($data['xing_url']) ? Purify::clean($data['xing_url'], self::$generalFieldsConfig): null,
            'user_id' => Purify::clean($data['user_id'], self::$generalFieldsConfig),
        ];
    }
}
