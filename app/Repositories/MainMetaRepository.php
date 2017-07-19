<?php

namespace App\Repositories;

use App\Models\MainMeta;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

/**
 * Class MainMetaRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class MainMetaRepository extends BaseRepository implements IRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
        'keywords',
        'google_analytics_id',
        'yandex_verification',
        'bing_verification',
        'page_main_title',
        'page_main_content',
        'addthisid',
        'twitter_username',
        'feedburner_feed_url',
        'disqus_shortname',
        'prevent_adblock_blocking',
        'language_code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MainMeta::class;
    }

    /**
     * Create main meta data.
     *
     * @param  array $data
     * @return MainMeta
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $mainMetaData = self::cleanInput($data);
        $mainMeta = parent::create($mainMetaData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($mainMeta, $mainMetaData);
        $mainMeta->save();
        return $this->parserResult($mainMeta);
    }


    /**
     * Update main meta data,
     *
     * @param  array $data
     * @param  $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $mainMetaData = self::cleanInput($data);
        $mainMeta = parent::update($mainMetaData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $mainMeta = $this->updateRelations($mainMeta, $mainMetaData);
        $mainMeta->save();
        return $this->parserResult($mainMeta);
    }

    /**
     * Sanitize inout for main meta.
     *
     * @param  array $input
     * @return array
     */
    public static function cleanInput(array $input)
    {
        $input['title'] = Purifier::clean($input['title'], 'generalFields');
        $input['description'] = Purifier::clean($input['description'], 'generalFields');
        $input['keywords'] = Purifier::clean($input['keywords'], 'generalFields');
        $input['google_analytics_id'] = Purifier::clean($input['google_analytics_id'], 'generalFields');
        $input['yandex_verification'] = Purifier::clean($input['yandex_verification'], 'generalFields');
        $input['bing_verification'] = Purifier::clean($input['bing_verification'], 'generalFields');
        $input['addthisid'] = Purifier::clean($input['addthisid'], 'generalFields');
        $input['twitter_username'] = Purifier::clean($input['twitter_username'], 'generalFields');
        $input['feedburner_feed_url'] = Purifier::clean($input['feedburner_feed_url'], 'generalFields');
        $input['disqus_shortname'] = Purifier::clean($input['disqus_shortname'], 'generalFields');
        $input['prevent_adblock_blocking'] = Purifier::clean($input['prevent_adblock_blocking'], 'generalFields');
        $input['page_main_title'] = Purifier::clean($input['page_main_title'], 'generalFields');
        $input['page_main_content'] = Purifier::clean($input['page_main_content']);
        $input['language_code'] = Purifier::clean($input['language_code'], 'generalFields');

        return $input;
    }
}
