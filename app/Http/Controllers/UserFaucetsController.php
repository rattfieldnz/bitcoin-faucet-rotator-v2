<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Faucets;
use App\Helpers\Functions\Http;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreateUserFaucetRequest;
use App\Http\Requests\UpdateUserFaucetRequest;
use App\Libraries\Seo\SeoConfig;
use App\Models\Faucet;
use App\Models\PaymentProcessor;
use App\Models\User;
use App\Repositories\UserFaucetRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserFaucetsController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class UserFaucetsController extends Controller
{
    private $userFaucetRepository;

    private $userRepository;

    private $faucetFunctions;

    /**
     * UserFaucetsController constructor.
     *
     * @param UserFaucetRepository $userFaucetRepo
     * @param UserRepository       $userRepository
     * @param Faucets              $faucetFunctions
     */
    public function __construct(
        UserFaucetRepository $userFaucetRepo,
        UserRepository $userRepository,
        Faucets $faucetFunctions
    ) {
        $this->userFaucetRepository = $userFaucetRepo;
        $this->userRepository = $userRepository;
        $this->faucetFunctions = $faucetFunctions;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the Faucet.
     *
     * @param  $userSlug
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function index($userSlug, Request $request)
    {
        $this->userFaucetRepository->pushCriteria(new RequestCriteria($request));
        $user = null;

        $user = User::where('slug', $userSlug)->withTrashed()->first();

        // If the assigned user doesn't exist, redirect to users listing instead, with 'not found' flash message.
        if (empty($user) || ($user->isDeleted() && !Auth::user()->isAnAdmin())) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        $message = null;
        if ($user->isDeleted()) {
            $message = 'The user has been temporarily deleted. You can restore the user or permanently delete them.';
        }

        if ($user->isAnAdmin()) {
            return redirect(route('faucets.index'));
        }

        if (Auth::guest() || (Auth::check() && (!Auth::user()->isAnAdmin() || $user->id != Auth::user()->id))) {
            $showFaucets = $user->faucets()
                ->wherePivot('referral_code', '!=', null)
                ->get();
        } else {
            $showFaucets = $user->faucets()->get();
        }

        $paymentProcessors = PaymentProcessor::orderBy('name', 'asc')->pluck('name', 'id');

        $seoConfig = new SeoConfig();
        $seoConfig->title = $user->user_name . "'s list of faucets (" . count($showFaucets) . ")";
        $seoConfig->description = "View " . $user->user_name .
            "'s list of faucets and claim some free Bitcoin! They currently have " .
            count($showFaucets) . " faucets.";
        $seoConfig->keywords = [$user->user_name, 'List of Faucets', $user->user_name . "'s faucets", "Bitcoin Faucets", "Get Free Bitcoin"];
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = $user->fullName();
        $seoConfig->currentUrl = route('users.faucets', ['userSlug' => $user->slug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription  ="List of Faucets";
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = $user->user_name . '-' . $user->id . '-faucets-list';

        return view('users.faucets.index')
            ->with('user', $user)
            ->with('faucets', $showFaucets)
            ->with('paymentProcessors', $paymentProcessors)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier)
            ->with('message', $message);
    }

    /**
     * Show the form for creating a new Faucet.
     *
     * @param  $userSlug
     * @return \Illuminate\View\View
     */
    public function create($userSlug)
    {
        $paymentProcessors = PaymentProcessor::orderBy('name', 'asc')->get();
        $faucetPaymentProcessorIds = null;
        $faucet = null;

        $faucets = Faucet::distinct()->orderBy('name', 'asc')->get();
        $userFaucets = null;

        $user = User::where('slug', $userSlug)->withTrashed()->first();

        // If the assigned user doesn't exist, redirect to users listing instead, with 'not found' flash message.
        if (empty($user) || $user->isDeleted() && !Auth::user()->isAnAdmin()) {
            flash('User not found.')->error();
            return redirect(route('users.index'));
        }

        if ($user->isAnAdmin()) {
            return redirect(route('faucets.create'));
        }

        if (Auth::user()->hasRole('user') || Auth::user()->isAnAdmin()) {
            $userFaucets = $this->faucetFunctions->getUserFaucets($user, true);
        } else {
            $userFaucets = $this->faucetFunctions->getUserFaucets($user, false);
        }

        $availableFaucets = $faucets->except($userFaucets->modelKeys());

        return view('users.faucets.create')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('faucetPaymentProcessorIds', $faucetPaymentProcessorIds)
            ->with('faucets', $availableFaucets)
            ->with('user', $user);
    }

    /**
     * Store a newly created Faucet in storage.
     *
     * @param  $userSlug
     * @param  CreateUserFaucetRequest $request
     * @return Response
     */
    public function store($userSlug, CreateUserFaucetRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $userSlug)->first();

        if (empty($user)) {
            flash('User not found.')->error();
            return redirect(route('users.index'));
        }

        if (Auth::user()->isAnAdmin() || Auth::user() == $user) {
            $input = $request->except('_token', 'payment_processor');

            $redirectRoute = route('users.faucets', $user->slug);

            $this->userFaucetRepository->create($input);

            if (!empty(request('payment-processor'))) {
                $slug = Purifier::clean(request('payment-processor'), 'generalFields');
                $paymentProcessor = PaymentProcessor::where('slug', $slug)->first();
            }

            if (!empty($user) && !empty($paymentProcessor)) {
                $redirectRoute = route(
                    'users.payment-processors.faucets',
                    [
                        'userSlug' => $user->slug,
                        'paymentProcessorSlug' => $paymentProcessor->slug
                    ]
                );
            }

            flash('Faucet saved successfully.')->success();

            return redirect($redirectRoute);
        } else {
            return abort(403);
        }
    }

    /**
     * Display the specified Faucet.
     *
     * @param  $userSlug
     * @param  $faucetSlug
     * @return \Illuminate\View\View
     */
    public function show($userSlug, $faucetSlug)
    {
        $user = null;
        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            $user = $this->userRepository->findByField('slug', $userSlug, true)->first();
        } else {
            $user = $this->userRepository->findByField('slug', $userSlug)->first();
        }

        //If there is no such user, about to 404 page.
        if (empty($user)) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->withTrashed()->first();

        $mainFaucet = Faucet::where('slug', $faucetSlug)->withTrashed()->first();

        $message = null;

        // If the visitor isn't authenticated, the user's faucet has no referral code, and main admin faucet exists.
        if (Auth::guest() && (!empty($mainFaucet) && empty($faucet->pivot->referral_code))) {
            flash('The faucet was not found')->error();
            return redirect(route('users.faucets', $user->slug));
        }

        // If the main admin faucet exists, and the user's faucet has no referral code.
        if (!empty($mainFaucet) && (!empty($faucet) && empty($faucet->pivot->referral_code))) {
            //If the visitor isn't authenticated.
            if (Auth::guest() || (!empty(Auth::user()) && Auth::user()->id != $user->id && !Auth::user()->isAnAdmin())) {
                flash('The faucet was not found')->error();
                return redirect(route('users.faucets', $user->slug));
            }
            //If the authenticated user is an owner or standard user.
            if (!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->hasRole('user')) || $user->id == Auth::user()->id) {
                if (Auth::user()->isAnAdmin()) {
                    $message = 'The user has not added a referral code for this faucet; however, you are able to add one for them if needed.';
                } elseif (Auth::user()->id == $user->id) {
                    $message = 'You have not added a referral code for this faucet; however, you are able to add one.';
                }
                Faucets::setMeta($faucet, $user);

                Faucets::setSecureFaucetIframe($user, $faucet);

                $disqusIdentifier = $user->user_name . '-' . $user->id . '-faucets-' . $faucet->slug;

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('faucetUrl', $faucet->url . Faucets::getUserFaucetRefCode($user, $faucet))
                    ->with('currentUrl', route('users.faucets.show', ['userSlug' => $user->slug, 'faucetSlug' => $faucet->slug]))
                    ->with('disqusIdentifier', $disqusIdentifier)
                    ->with('message', $message)
                    ->with('canShowInIframe', Http::canShowInIframes($faucet->url));
            }
        } // If the user's faucet exists, and main admin faucet is soft-deleted.
        elseif (!empty($faucet) && $mainFaucet->isDeleted()) {
            //If the authenticated user is an owner or standard user, or the faucet's user is currently authenticated.
            if (!empty(Auth::user()) && (Auth::user()->isAnAdmin() || Auth::user()->hasRole('user')) || $user === Auth::user()) {
                if (Auth::user()->id == $user->id) {
                    $message = 'The owner of this rotator has deleted the faucet you requested. You can contact them if you would like it to be restored.';
                } elseif (Auth::user()->isAnAdmin()) {
                    $mainFaucetLink = link_to_route(
                        'faucets.show',
                        'restore that faucet',
                        ['slug' => $mainFaucet->slug],
                        ['target' => '_blank', 'title' => 'Restore the main faucet, as created by admin.']
                    );

                    $message = 'You have deleted the main faucet this user has used as their faucet. You can ' .
                        $mainFaucetLink . ' or permanently delete it.';
                }

                Faucets::setMeta($faucet, $user);

                Faucets::setSecureFaucetIframe($user, $faucet);

                $disqusIdentifier = $user->user_name . '-' . $user->id . '-faucets-' . $faucet->slug;

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('faucetUrl', $faucet->url . Faucets::getUserFaucetRefCode($user, $faucet))
                    ->with('currentUrl', route('users.faucets.show', ['userSlug' => $user->slug, 'faucetSlug' => $faucet->slug]))
                    ->with('disqusIdentifier', $disqusIdentifier)
                    ->with('message', $message)
                    ->with('canShowInIframe', Http::canShowInIframes($faucet->url));
            } else {
                flash('The faucet was not found')->error();
                return redirect(route('users.faucets', $user->slug));
            }
        } else {
            //If user faucet exists
            if (!empty($faucet)) {
                //dd($faucet);
                Faucets::setMeta($faucet, $user);

                Faucets::setSecureFaucetIframe($user, $faucet);

                $disqusIdentifier = $user->user_name . '-' . $user->id . '-faucets-' . $faucet->slug;

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('faucetUrl', $faucet->url . Faucets::getUserFaucetRefCode($user, $faucet))
                    ->with('currentUrl', route('users.faucets.show', ['userSlug' => $user->slug, 'faucetSlug' => $faucet->slug]))
                    ->with('disqusIdentifier', $disqusIdentifier)
                    ->with('message', $message)
                    ->with('canShowInIframe', Http::canShowInIframes($faucet->url));
            } else {
                flash('The faucet was not found')->error();
                return redirect(route('users.faucets', $user->slug));
            }
        }
    }

    public function updateMultiple($userSlug, Request $request)
    {
        $user = User::where('slug', $userSlug)->withTrashed()->first();

        $input = $request->except('_token', '_method');

        //If there is no such user, about to 404 page.
        if (empty($user) || ($user->isDeleted() && !Auth::user()->isAnAdmin())) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        $redirectRoute = route('users.faucets', $user->slug);

        $userFaucetIds = $input['faucet_id'];
        $referralCodes = $input['referral_code'];

        for ($i = 0; $i < count($userFaucetIds); $i++) {
            $referralCode = !empty($referralCodes[$i]) ? $referralCodes[$i] : null;

            $faucet = Faucet::where('id', '=', intval($userFaucetIds[$i]))->first();

            Faucets::setUserFaucetRefCode($user, $faucet, $referralCode);
        }

        if (!empty(request('payment_processor'))) {
            $slug = Purifier::clean(request('payment_processor'), 'generalFields');
            $paymentProcessor = PaymentProcessor::where('slug', $slug)->first();

            if (!empty($paymentProcessor)) {
                $redirectRoute = route(
                    'users.payment-processors.faucets',
                    [
                        'userSlug' => $user->slug,
                        'paymentProcessorSlug' => $paymentProcessor->slug
                    ]
                );
            }
        }

        if (!empty(request('current_route_name'))) {
            $currentRouteName = Purifier::clean(request('current_route_name'), 'generalFields');

            if ($currentRouteName == 'users.show') {
                $redirectRoute = route('users.show', ['slug' => $user->slug]) . "#faucets";
            }
        }

        flash('The referral codes for the selected faucets were successfully updated!')->success();

        return redirect($redirectRoute);
    }
}
