<?php

namespace App\Http\Controllers;

use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Libraries\Seo\SeoConfig;
use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\PaymentProcessor;
use Carbon\Carbon;
use App\Helpers\Functions\Users;
use Illuminate\Support\Facades\Config;

class RotatorController extends Controller
{

    public function index()
    {
        $mainMeta = MainMeta::firstOrFail();
        $pageTitle = null;
        $content = null;
        if (!empty($mainMeta)) {

            $seoConfig = new SeoConfig();
            $seoConfig->title = $mainMeta->title;
            $seoConfig->description = $mainMeta->description;
            $seoConfig->keywords = array_map('trim', explode(',', $mainMeta->keywords));
            $seoConfig->publishedTime = Carbon::now()->toW3cString();
            $seoConfig->modifiedTime = Carbon::now()->toW3cString();
            $seoConfig->authorName = Users::adminUser()->fullName();
            $seoConfig->currentUrl = env('APP_URL');
            $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
            $seoConfig->categoryDescription = "Bitcoin Faucet Rotator";

            $pageTitle = $mainMeta->page_main_title;
            $content = $mainMeta->page_main_content;

            WebsiteMeta::setCustomMeta($seoConfig);
        }

        $config = Config::get('secure-headers.csp.child-src.allow');

        $faucets = Faucet::where('is_paused', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        foreach ($faucets as $f) {
            array_push($config, parse_url($f->url)['host']);
        }

        Config::set('secure-headers.csp.child-src.allow', $config);

        return view('rotator.index')
            ->with('pageTitle', $pageTitle)
            ->with('content', $content);
    }

    public function getPaymentProcessorFaucetRotator($paymentProcessorSlug)
    {
        $paymentProcessor = PaymentProcessor::where('slug', '=', $paymentProcessorSlug)->firstOrFail();

        if (empty($paymentProcessor)) {
            abort(404, "The payment processor cannot be found.");
        }

        $seoConfig = new SeoConfig();
        $seoConfig->title = $paymentProcessor->name . " Faucet Rotator (" . count($paymentProcessor->faucets) . " available faucet/s).";
        $seoConfig->description = "Come and get free satoshis from around " .
            count($paymentProcessor->faucets) . " faucets in the " .
            $paymentProcessor->name . " Faucet Rotator.";
        $seoConfig->keywords = array_map('trim', explode(',', $paymentProcessor->meta_keywords));
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('payment-processors.rotator', ['slug' => $paymentProcessor->slug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "Bitcoin Faucet Rotator";
        WebsiteMeta::setCustomMeta($seoConfig);

        $config = Config::get('secure-headers.csp.child-src.allow');
        $faucets = $paymentProcessor->faucets()
            ->where('is_paused', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        foreach ($faucets as $f) {
            array_push($config, parse_url($f->url)['host']);
        }
        //dd($config);
        Config::set('secure-headers.csp.child-src.allow', $config);

        return view('payment_processors.rotator.index')
            ->with('paymentProcessor', $paymentProcessor);
    }
}
