<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class MainMeta
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $google_analytics_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $yandex_verification
 * @property string|null $bing_verification
 * @property string $page_main_title
 * @property string|null $page_main_content
 * @property string|null $addthisid
 * @property string|null $twitter_username
 * @property string|null $feedburner_feed_url
 * @property string|null $disqus_shortname
 * @property bool $prevent_adblock_blocking
 * @property Carbon|null $deleted_at
 * @property string|null $language_code
 * @property-read Language $language
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta newQuery()
 * @method static Builder|MainMeta onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereAddthisid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereBingVerification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereDisqusShortname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereFeedburnerFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereGoogleAnalyticsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta wherePageMainContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta wherePageMainTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta wherePreventAdblockBlocking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereTwitterUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainMeta whereYandexVerification($value)
 * @method static Builder|MainMeta withTrashed()
 * @method static Builder|MainMeta withoutTrashed()
 * @mixin Eloquent
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
