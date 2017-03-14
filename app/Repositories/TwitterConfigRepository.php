<?php

namespace App\Repositories;

use App\Models\TwitterConfig;
use InfyOm\Generator\Common\BaseRepository;

class TwitterConfigRepository extends BaseRepository
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
}
