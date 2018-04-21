<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Faucets;
use App\Helpers\Functions\PaymentProcessors;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Libraries\Seo\SeoConfig;
use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\PaymentProcessor;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use App\Helpers\Functions\Users;
use Illuminate\Support\Facades\Config;

class RotatorController extends Controller
{
    private $userRepository;
    private $userFunctions;

    public function __construct(UserRepository $userRepo, Users $userFunctions)
    {
        $this->userRepository = $userRepo;
        $this->userFunctions = $userFunctions;
    }

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

        $faucets = Users::adminUser()->faucets()->where('is_paused', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        Faucets::setMultipleFaucetsCsp($faucets);

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
        $seoConfig->title = $paymentProcessor->name . " Faucet Rotator (" . count($paymentProcessor->faucets) . " available faucet/s)";
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

        $disqusIdentifier = Users::adminUser()->user_name .
            '-' . Users::adminUser()->id .
            '-payment-processors-' . $paymentProcessor->slug . '-rotator';

        $faucets = $paymentProcessor->faucets()
            ->where('is_paused', '=', false)
            ->where('has_low_balance', '=', false)
            ->where('deleted_at', '=', null)
            ->get(['url']);

        Faucets::setMultipleFaucetsCsp($faucets);

        return view('payment_processors.rotator.index')
            ->with('paymentProcessor', $paymentProcessor)
            ->with('currentUrl', route('payment-processors.rotator', ['slug' => $paymentProcessor->slug]))
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    public function getUserFaucetRotator($userSlug)
    {
        $user = $this->userRepository->findByField('slug', $userSlug)->first();
        if (empty($user)) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        if ($user->isAnAdmin()) {
            return redirect(route('home'));
        }

        $faucets = $user->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->wherePivot('referral_code', '!=', null);

        $faucetKeywords = $faucets->pluck('faucets.name')->toArray();
        array_push($faucetKeywords, $user->user_name);

        $seoConfig = new SeoConfig();
        $seoConfig->title = $user->user_name . "'s Rotator (" . count($faucets->get()) . " faucets)";
        $seoConfig->description = "Claim your free bitcoins from " . $user->user_name . "'s Bitcoin Faucet Rotator. " .
                                  "There are currently " . count($faucets->get()) . " faucets in their rotator.";
        $seoConfig->keywords = $faucetKeywords;
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = $user->fullName();
        $seoConfig->currentUrl = route('users.rotator', ['userSlug' => $user->slug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "User Bitcoin Faucet Rotator";
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = 'users-' . $user->slug . $user->id . 'faucet-rotator';

        Faucets::setMultipleFaucetsCsp($faucets->get());

        return view('users.rotator.index')
            ->with('userName', $user->user_name)
            ->with('userSlug', $user->slug)
            ->with('faucetsCount', count($faucets->get()))
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    function getUserPaymentProcessorFaucetRotator($userSlug, $paymentProcessorSlug)
    {
        $user = $this->userRepository->findByField('slug', $userSlug)->where('deleted_at', '=', null)->first();
        $paymentProcessor = PaymentProcessor::where('slug', '=', $paymentProcessorSlug)->first();

        if (empty($user)) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        if (empty($paymentProcessor)) {
            flash('Payment processor not found')->error();
            return redirect(route('users.index'));
        }

        if ($user->isAnAdmin()) {
            return redirect(route('payment-processors.rotator', ['slug' => $paymentProcessor->slug]));
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);
        $faucetKeywords = $faucets->pluck('faucets.name')->toArray();
        array_push($faucetKeywords, $user->user_name);
        array_push($faucetKeywords, $paymentProcessor->slug);

        $seoConfig = new SeoConfig();
        $seoConfig->title = $user->user_name . "'s " . $paymentProcessor->name . " Rotator";
        $seoConfig->description = "Claim your free bitcoins from " . $user->user_name . "'s  . $paymentProcessor->name . Bitcoin Faucet Rotator. " .
            "There are currently " . count($faucets) . " faucets in this rotator.";
        $seoConfig->keywords = $faucetKeywords;
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = $user->fullName();
        $seoConfig->currentUrl = route(
            'users.payment-processors.rotator',
            [
                    'userSlug' => $user->slug,
                    'paymentProcessorSlug' => $paymentProcessor->slug
                ]
        );
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "User Bitcoin Faucet Rotator";
        WebsiteMeta::setCustomMeta($seoConfig);

        Faucets::setMultipleFaucetsCsp($faucets);

        $disqusIdentifier = 'users-' . $user->slug . $user->id . '-payment-processor-' . $paymentProcessor->slug . '-rotator';

        return view('users.payment_processors.rotator.index')
            ->with('userSlug', $user->slug)
            ->with('paymentProcessorSlug', $paymentProcessor->slug)
            ->with('userName', $user->user_name)
            ->with('faucetsCount', count($faucets))
            ->with('paymentProcessorName', $paymentProcessor->name)
            ->with('currentUrl', route('users.payment-processors.rotator', ['userSlug' =>$user->slug, 'paymentProcessorSlug' => $paymentProcessor->slug]))
            ->with('disqusIdentifier', $disqusIdentifier);
    }
}
