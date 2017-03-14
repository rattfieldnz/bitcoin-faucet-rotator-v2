<?php

namespace App\Repositories;

use App\Models\MainMeta;
use InfyOm\Generator\Common\BaseRepository;

class MainMetaRepository extends BaseRepository
{
    /**
     * @var array
     */
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
        'prevent_adblock_blocking'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MainMeta::class;
    }
}
