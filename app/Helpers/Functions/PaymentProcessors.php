<?php

namespace App\Helpers\Functions;

use App\Models\MainMeta;
use App\Models\PaymentProcessor;
use App\Models\User;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;

/**
 * Class PaymentProcessors
 *
 * A helper class to handle extra functionality
 * related to currently stored faucets.
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers\Functions
 */
class PaymentProcessors
{
    /**
     * Function to set meta data properties for SEO.
     *
     * @param \App\Models\PaymentProcessor $paymentProcessor
     * @param \App\Models\User             $user
     * @return void
     */
    public static function setMeta(PaymentProcessor $paymentProcessor, User $user) {

        if(!empty($paymentProcessor) && !empty($user)) {

            $title = $paymentProcessor->meta_title;
            $description = $paymentProcessor->meta_description;
            $keywords = array_map('trim', explode(',', $paymentProcessor->meta_keywords));
            $publishedTime = $paymentProcessor->created_at->toW3CString();
            $modifiedTime = $paymentProcessor->updated_at->toW3CString();
            $author = $user->fullName();
            $currentUrl = env('APP_URL') . '/' . $paymentProcessor->slug;
            $image = env('APP_URL') . '/assets/images/og/bitcoin.png';

            SEOMeta::setTitle($title)
                ->setTitleSeparator('|')
                ->setDescription($description)
                ->setKeywords($keywords)
                ->addMeta('author', $author, 'name')
                ->addMeta('revised', $modifiedTime, 'name')
                ->addMeta('name', $title, 'itemprop')
                ->addMeta('description', $description, 'itemprop')
                ->addMeta('image', $image, 'itemprop')
                ->addMeta('fb:admins', "871754942861947", 'property')
                ->setCanonical($currentUrl);

            OpenGraph::setTitle($title)
                ->setUrl($currentUrl)
                ->setSiteName(MainMeta::first()->page_main_title)
                ->addProperty("locale", MainMeta::first()->language()->first()->isoCode())
                ->setDescription($description)
                ->setType('article')
                ->addImage($image)
                ->setArticle([
                    'author' => $author,
                    'published_time' => $publishedTime,
                    'modified_time' => $modifiedTime,
                    'section' => 'Crypto Payment Processors',
                    'tag' => $keywords
                ]);

            TwitterCard::setType('summary')
                ->addImage($image)
                ->setTitle($title)
                ->setDescription($description)
                ->setUrl($currentUrl)
                ->setSite(MainMeta::first()->twitter_username);
        }
    }
}