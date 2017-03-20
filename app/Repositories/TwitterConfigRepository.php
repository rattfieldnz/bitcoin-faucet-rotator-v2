<?php

namespace App\Repositories;

use App\Models\TwitterConfig;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

class TwitterConfigRepository extends BaseRepository implements IRepository
{
    /**
     * @var array
     */
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
     * Create main meta data.
     *
     * @param  array  $data
     * @return TwitterConfig
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
     * Update main meta data,
     * @param array $data
     * @param $id
     * @return mixed
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

    static function cleanInput(array $input)
    {
        $input['consumer_key'] = Purifier::clean($input['consumer_key'], 'generalFields');
        $input['consumer_key_secret'] = Purifier::clean($input['consumer_key_secret'], 'generalFields');
        $input['access_token'] = Purifier::clean($input['access_token'], 'generalFields');
        $input['access_token_secret'] = Purifier::clean($input['access_token_secret'], 'generalFields');
        $input['user_id'] = Purifier::clean($input['user_id'], 'generalFields');

        return $input;
    }
}
