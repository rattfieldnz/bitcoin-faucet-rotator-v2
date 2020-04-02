<?php

namespace App\Repositories;

use App\Models\TwitterConfig;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class TwitterConfigRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class TwitterConfigRepository extends Repository implements IRepository
{
    protected $fieldSearchable = [
        'consumer_key',
        'consumer_key_secret',
        'access_token',
        'access_token_secret',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TwitterConfig::class;
    }

    /**
     * Create Twitter config data.
     *
     * @param array $data
     * @return TwitterConfig
     * @throws ValidatorException
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $twitterConfigData = self::cleanInput($data);
        $twitterConfig = parent::create($twitterConfigData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($twitterConfig, $twitterConfigData);
        $twitterConfig->save();
        return $this->parserResult($twitterConfig);
    }


    /**
     * Update Twitter config data.
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
        $twitterConfigData = self::cleanInput($data);
        $twitterConfig = parent::update($twitterConfigData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $twitterConfig = $this->updateRelations($twitterConfig, $twitterConfigData);
        $twitterConfig->save();
        return $this->parserResult($twitterConfig);
    }

    /**
     * Sanitize Twitter config data.
     *
     * @param array $input
     *
     * @return array
     */
    public static function cleanInput(array $input)
    {
        $input['consumer_key'] = Purify::clean($input['consumer_key'], self::$generalFieldsConfig);
        $input['consumer_key_secret'] = Purify::clean($input['consumer_key_secret'], self::$generalFieldsConfig);
        $input['access_token'] = Purify::clean($input['access_token'], self::$generalFieldsConfig);
        $input['access_token_secret'] = Purify::clean($input['access_token_secret'], self::$generalFieldsConfig);
        $input['user_id'] = Purify::clean($input['user_id'], self::$generalFieldsConfig);

        return $input;
    }
}
