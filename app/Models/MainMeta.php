<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MainMeta
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 */
class MainMeta extends Model
{
    use SoftDeletes;

    public $table = 'main_meta';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'keywords' => 'string',
        'google_analytics_id' => 'string',
        'yandex_verification' => 'string',
        'bing_verification' => 'string',
        'page_main_title' => 'string',
        'page_main_content' => 'string',
        'addthisid' => 'string',
        'twitter_username' => 'string',
        'feedburner_feed_url' => 'string',
        'disqus_shortname' => 'string',
        'prevent_adblock_blocking' => 'boolean',
        'language_code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'min:10|max:70',
        'description' => 'min:20|max:160',
        'keywords' => 'min:3|max:255',
        'google_analytics_code' => 'max:20',
        'yandex_verification' => 'max:70',
        'bing_verification' => 'max:70',
        'addthisid' => 'max:35',
        'twitter_username' => 'max:35',
        'feedburner_feed_url' => 'max:255',
        'disqus_shortname' => 'max:100',
        'page_main_title' => 'required|min:15|max:100',
        'prevent_adblock_blocking' => 'min:0|max:1',
        'language_code' => 'sometimes|required|exists:languages,iso_code'
    ];

    public function language()
    {
        return $this->hasOne(Language::class, 'iso_code', 'language_code');
    }
}
