<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Faucets;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\PaymentProcessor;
use Carbon\Carbon;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RotatorController extends Controller
{

    public function index()
    {
        $mainMeta = MainMeta::firstOrFail();
        $pageTitle = null;
        $content = null;
        if (!empty($mainMeta)) {
            $title = $mainMeta->title;
            $description = $mainMeta->description;
            $keywords = explode(",", $mainMeta->keywords);
            $publishedTime = Carbon::now()->toW3cString();
            $modifiedTime = Carbon::now()->toW3cString();
            $author = Users::adminUser()->fullName();
            $currentUrl = env('APP_URL');
            $image = env('APP_URL') . '/assets/images/og/bitcoin.png';
            $categoryDescription = "Bitcoin Faucet Rotator";
            $pageTitle = $mainMeta->page_main_title;
            $content = $mainMeta->page_main_content;

            WebsiteMeta::setCustomMeta($title, $description, $keywords, $publishedTime, $modifiedTime, $author, $currentUrl, $image, $categoryDescription);
        }

        $config = Config::get('secure-headers.csp.child-src.allow');

        $faucets = Faucet::where('is_paused', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        foreach($faucets as $f){
            array_push($config, parse_url($f->url)['host']);
        }

        Config::set('secure-headers.csp.child-src.allow', $config);

        return view('rotator.index')
            ->with('pageTitle', $pageTitle)
            ->with('content', $content);
    }

    public function getPaymentProcessorFaucetRotator($paymentProcessorSlug){
        $paymentProcessor = PaymentProcessor::where('slug', '=', $paymentProcessorSlug)->firstOrFail();

        if(empty($paymentProcessor)){
            abort(404, "The payment processor cannot be found.");
        }

        $title = $paymentProcessor->name . " Faucet Rotator (" . count($paymentProcessor->faucets) . " available faucet/s).";
        $description = "Come and get free satoshis from around " . count($paymentProcessor->faucets) . " faucets in the " . $paymentProcessor->name . " Faucet Rotator.";
        $keywords = array_map('trim', explode(',', $paymentProcessor->meta_keywords));
        $publishedTime = Carbon::now()->toW3cString();
        $modifiedTime = Carbon::now()->toW3cString();
        $author = Users::adminUser()->fullName();
        $currentUrl = route('payment-processors.rotator', ['slug' => $paymentProcessor->slug]);
        $image = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $categoryDescription = "Bitcoin Faucet Rotator";

        WebsiteMeta::setCustomMeta($title, $description, $keywords, $publishedTime, $modifiedTime, $author, $currentUrl, $image, $categoryDescription);

        $config = Config::get('secure-headers.csp.child-src.allow');
        $faucets = $paymentProcessor->faucets()
            ->where('has_low_balance', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        foreach($faucets as $f){
            array_push($config, parse_url($f->url)['host']);
        }
        //dd($config);
        Config::set('secure-headers.csp.child-src.allow', $config);

        return view('payment_processors.rotator.index')
            ->with('paymentProcessor', $paymentProcessor);


    }
}
