<?php

namespace App\Helpers\Functions;

use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\PaymentProcessor;
use App\Models\User;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Facades\Auth;

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
    public static function setMeta(PaymentProcessor $paymentProcessor, User $user)
    {

        if (!empty($paymentProcessor) && !empty($user)) {
            $title = $paymentProcessor->meta_title . " " . env('APP_TITLE_SEPARATOR') . " " . env('APP_TITLE_APPEND');
            $description = $paymentProcessor->meta_description;
            $keywords = array_map('trim', explode(',', $paymentProcessor->meta_keywords));
            $publishedTime = $paymentProcessor->created_at->toW3CString();
            $modifiedTime = $paymentProcessor->updated_at->toW3CString();
            $author = $user->fullName();
            $currentUrl = route('payment-processors.show', ['slug' => $paymentProcessor->slug]);
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

    public static function countUserPaymentProcessorFaucets(User $user, PaymentProcessor $paymentProcessor)
    {
        if (empty($user) || empty($paymentProcessor)) {
            return 0;
        }

        $userFaucets = User::where('id', '=', $user->id)
            ->first()
            ->faucets();

        $paymentProcessorFaucets = $paymentProcessor
            ->faucets()
            ->get()
            ->whereIn('id', $userFaucets->select('id')->pluck('id')->toArray());

        if(Auth::check() && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)){
            $paymentProcessorFaucets = $paymentProcessor
                ->faucets()
                ->wherePivot('referral_code', '!=', null)
                ->get()
                ->whereIn('id', $userFaucets->select('id')->pluck('id')->toArray());
        }

        $count = count($paymentProcessorFaucets);

        if (!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
            foreach ($userFaucets as $f) {
                $mainFaucet = Faucet::where('id', '=', $f->id)->withTrashed()->first();

                if ($mainFaucet->isDeleted()) {
                    $count += 1;
                }
            }
        }


        return $count;
    }

    public static function userPaymentProcessorFaucets(User $user, PaymentProcessor $paymentProcessor)
    {
        if (empty($user) || empty($paymentProcessor)) {
            return null;
        }

        $userFaucets = User::where('id', '=', $user->id)
            ->first()
            ->faucets();

        $faucets = $paymentProcessor->faucets();

        if (!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
            $userFaucets = $userFaucets->withTrashed()->get()->pluck('id')->toArray();

            return $faucets->withTrashed()->get()->whereIn('id', $userFaucets);
        } else {
            $userFaucets = $userFaucets
                ->wherePivot('referral_code', '!=', null)
                ->get()
                ->pluck('id')
                ->toArray();

            return $faucets->get()->whereIn('id', $userFaucets);
        }
    }

    public static function userPaymentProcessorFaucet(User $user, PaymentProcessor $paymentProcessor, Faucet $faucet)
    {
        if (empty($user) || empty($paymentProcessor) ||empty($faucet)) {
            return null;
        }

        $userFaucet = User::where('id', '=', $user->id)
            ->first()
            ->faucets()
            ->where('faucets.slug', '=', $faucet->slug);

        $faucets = $paymentProcessor->faucets();

        if (!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
            $faucetId = $userFaucet->withTrashed()->get()->pluck('id')->first();

            return $faucets->withTrashed()->where('faucets.id', '=', $faucetId)->first();
        } else {
            $faucetId = $userFaucet
                ->wherePivot('referral_code', '!=', null)
                ->get()
                ->pluck('id')
                ->first();

            return $faucets->where('faucets.id', '=', $faucetId)->first();
        }
    }
}
