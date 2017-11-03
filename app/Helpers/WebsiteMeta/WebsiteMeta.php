<?php namespace App\Helpers\WebsiteMeta;

use App\Libraries\Seo\SeoConfig;
use App\Models\MainMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Carbon\Carbon;
use Exception;

/**
 * Class WebsiteMeta
 *
 * A class to handle retrieval of a given URL's
 * website meta details
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers\WebsiteMeta
 */
class WebsiteMeta
{
    private $url;
    private $urlMetaUrl = 'https://api.urlmeta.org?url=';
    private $urlContents;

    /**
     * WebsiteMeta constructor.
     *
     * @param  $url
     * @throws Exception
     */
    public function __construct($url)
    {
        ini_set("allow_url_fopen", 1);
        $this->url = $this->urlMetaUrl . $url;
        $this->contents = $this->getUrlContents($this->url);
    }

    /**
     * A function to obtain title information from the current
     * URL's title meta tag.
     *
     * @return false|null|string
     */
    public function title()
    {
        try {
            return $this->contents->meta->title != null ? $this->contents->meta->title : "";
        } catch (Exception $e) {
            return "";
        }
    }

    /**
     * A function to obtain keywords from the current URL's
     * keywords meta tag.
     *
     * @return string
     */
    public function keywords()
    {
        try {
            return $this->contents->meta->keywords != null ? $this->contents->meta->keywords : "";
        } catch (Exception $e) {
            return "";
        }
    }

    /**
     * A function to retrieve the meta description of
     * the current URL.
     *
     * @return false|null|string
     */
    public function description()
    {
        try {
            return $this->contents->meta->description != null ? $this->contents->meta->description : "";
        } catch (Exception $e) {
            return "";
        }
    }

    /**
     * Returns search engine verification ids.
     *
     * @return array
     */
    public static function seVerificationIds()
    {
        return [
            'yandex_verification' => MainMeta::firstOrFail()->yandex_verification,
            'bing_verification' => MainMeta::firstOrFail()->bing_verification
        ];
    }

    /**
     * Returns the AddThis id.
     *
     * @return mixed
     */
    public static function addThisId()
    {
        return MainMeta::firstOrFail()->addthisid;
    }

    /**
     * Returns the Twitter username.
     *
     * @return mixed
     */
    public static function twitterUsername()
    {
        return MainMeta::firstOrFail()->twitter_username;
    }

    /**
     * Returns the Feedburner feed URL.
     *
     * @return mixed
     */
    public static function feedburnerFeedUrl()
    {
        return MainMeta::firstOrFail()->feedburner_feed_url;
    }

    public static function disqusShortName()
    {
        $shortName = env('DISQUS_SHORTNAME');
        return !empty(MainMeta::firstOrFail()->disqus_shortname) ?
            MainMeta::firstOrFail()->disqus_shortname :
            $shortName;
    }

    public static function activatedAdBlockBlocking()
    {
        return MainMeta::firstOrFail()->prevent_adblock_blocking;
    }

    /**
     * @param \App\Libraries\Seo\SeoConfig $seoConfig
     */
    public static function setCustomMeta(SeoConfig $seoConfig)
    {

        SEOMeta::setTitle($seoConfig->pageTitle())
            ->setDescription($seoConfig->description)
            ->setKeywords($seoConfig->keywords)
            ->addMeta('author', $seoConfig->authorName, 'name')
            ->addMeta('revised', $seoConfig->modifiedTime, 'name')
            ->addMeta('name', $seoConfig->title, 'itemprop')
            ->addMeta('description', $seoConfig->description, 'itemprop')
            ->addMeta('image', $seoConfig->imagePath, 'itemprop')
            ->addMeta('fb:admins', "871754942861947", 'property')
            ->setCanonical($seoConfig->currentUrl);

        OpenGraph::setTitle($seoConfig->pageTitle())
            ->setUrl($seoConfig->currentUrl)
            ->setSiteName(MainMeta::first()->page_main_title)
            ->addProperty("locale", MainMeta::first()->language()->first()->isoCode())
            ->setDescription($seoConfig->description)
            ->setType('article')
            ->addImage($seoConfig->imagePath)
            ->setArticle([
                'author' => $seoConfig->authorName,
                'published_time' => $seoConfig->publishedTime,
                'modified_time' => $seoConfig->modifiedTime,
                'section' => $seoConfig->categoryDescription,
                'tag' => $seoConfig->keywords
            ]);

        TwitterCard::setType('summary')
            ->addImage($seoConfig->imagePath)
            ->setTitle($seoConfig->pageTitle())
            ->setDescription($seoConfig->description)
            ->setUrl($seoConfig->currentUrl)
            ->setSite(MainMeta::first()->twitter_username);
    }

    private function getUrlContents($url)
    {
        $content = file_get_contents($url);
        return json_decode($content);
    }
}
