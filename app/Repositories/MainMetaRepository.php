<?php

namespace App\Repositories;

use App\Models\MainMeta;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class MainMetaRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class MainMetaRepository extends Repository implements IRepository
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
     * @param array $data
     * @return MainMeta
     * @throws ValidatorException
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
     * @param array $data
     * @param  $id
     * @return mixed
     * @throws ValidatorException
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
        $input['title'] = Purify::clean($input['title'], self::$generalFieldsConfig);
        $input['description'] = Purify::clean($input['description'], self::$generalFieldsConfig);
        $input['keywords'] = Purify::clean($input['keywords'], self::$generalFieldsConfig);
        $input['google_analytics_id'] = Purify::clean($input['google_analytics_id'], self::$generalFieldsConfig);
        $input['yandex_verification'] = Purify::clean($input['yandex_verification'], self::$generalFieldsConfig);
        $input['bing_verification'] = Purify::clean($input['bing_verification'], self::$generalFieldsConfig);
        $input['addthisid'] = Purify::clean($input['addthisid'], self::$generalFieldsConfig);
        $input['twitter_username'] = Purify::clean($input['twitter_username'], self::$generalFieldsConfig);
        $input['feedburner_feed_url'] = Purify::clean($input['feedburner_feed_url'], self::$generalFieldsConfig);
        $input['disqus_shortname'] = Purify::clean($input['disqus_shortname'], self::$generalFieldsConfig);
        $input['prevent_adblock_blocking'] = Purify::clean($input['prevent_adblock_blocking'], self::$generalFieldsConfig);
        $input['page_main_title'] = Purify::clean($input['page_main_title'], self::$generalFieldsConfig);
        $input['page_main_content'] = Purify::clean($input['page_main_content']);
        $input['language_code'] = Purify::clean($input['language_code'], self::$generalFieldsConfig);

        return $input;
    }
}
