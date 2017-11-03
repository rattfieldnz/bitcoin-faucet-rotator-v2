<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Helpers\Functions;
use App\Helpers\Functions\PaymentProcessors;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreatePaymentProcessorRequest;
use App\Http\Requests\UpdatePaymentProcessorRequest;
use App\Libraries\Seo\SeoConfig;
use App\Models\PaymentProcessor;
use App\Repositories\PaymentProcessorRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Helpers\Functions\Users;

/**
 * Class PaymentProcessorController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class PaymentProcessorController extends AppBaseController
{
    private $paymentProcessorRepository;
    private $userFunctions;

    /**
     * PaymentProcessorController constructor.
     *
     * @param \App\Repositories\PaymentProcessorRepository $paymentProcessorRepo
     * @param \App\Helpers\Functions\Users                     $userFunctions
     */
    public function __construct(PaymentProcessorRepository $paymentProcessorRepo, Users $userFunctions)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepo;
        $this->userFunctions = $userFunctions;
        $this->middleware('auth', ['except' => ['index', 'show', 'faucets', 'userPaymentProcessors', 'userPaymentProcessorFaucets']]);
    }

    /**
     * Display a listing of the PaymentProcessor.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->paymentProcessorRepository->pushCriteria(new RequestCriteria($request));

        $paymentProcessors = null;
        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            $paymentProcessors = $this->paymentProcessorRepository->withTrashed()->get();
        } else {
            $paymentProcessors = $this->paymentProcessorRepository->all();
        }

        $seoConfig = new SeoConfig();
        $seoConfig->title = "Listing of faucet payment processors (" . count($paymentProcessors) . ")";
        $seoConfig->description = "This page shows a list of payment processors which are currently in use in the system. There are " .
            count($paymentProcessors) . " payment processors.";
        $seoConfig->keywords = $paymentProcessors->pluck('name')->toArray();
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('payment-processors.index');
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = 'Crypto Payment Processors';
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = Users::adminUser()->user_name . '-' . Users::adminUser()->id . '-payment-processors-index';

        return view('payment_processors.index')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Show the form for creating a new PaymentProcessor.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.create', [], []);
        $paymentProcessor = null;
        return view('payment_processors.create')->with('paymentProcessor');
    }

    /**
     * Store a newly created PaymentProcessor in storage.
     *
     * @param CreatePaymentProcessorRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentProcessorRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.store', [], []);
        $input = $request->all();

        $paymentProcessor = $this->paymentProcessorRepository->create($input);

        flash('Payment Processor saved successfully.')->success();

        activity(Constants::ADMIN_PAYMENT_PROCESSOR_LOG)
            ->performedOn($paymentProcessor)
            ->causedBy(Auth::user())
            ->log("The payment processor ':subject.name' was added to the collection by :causer.user_name");

        return redirect(route('payment-processors.index'));
    }

    /**
     * Display the specified PaymentProcessor.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function show($slug)
    {
        $paymentProcessor = null;
        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, true)->first();
        } else {
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, false)->first();
        }

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        PaymentProcessors::setMeta($paymentProcessor, Users::adminUser());

        $disqusIdentifier = Users::adminUser()->user_name .
            '-' . Users::adminUser()->id .
            '-payment-processors-' . $paymentProcessor;

        return view('payment_processors.show')
            ->with('paymentProcessor', $paymentProcessor)
            ->with('currentUrl', route('payment-processors.show', ['slug' => $paymentProcessor->slug]))
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Show main faucets associated with a payment processor.
     *
     * @param  $slug
     * @return PaymentProcessorController|\Illuminate\View\View
     */
    public function faucets($slug)
    {
        $paymentProcessor = null;
        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            $paymentProcessor = PaymentProcessor::where('slug', '=', $slug)->withTrashed()->first();
        } else {
            $paymentProcessor = PaymentProcessor::where('slug', '=', $slug)->first();
        }

        $faucets = null;

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $faucets = collect();

        if (!$paymentProcessor->isDeleted()) {
            $faucets = $paymentProcessor->faucets()->get();
        }

        $message = null;
        if ($paymentProcessor->isDeleted()) {
            $message = "The payment processor has been temporarily deleted. Any associated faucets will show again once this payment processor has been restored.";
        }

        $seoConfig = new SeoConfig();
        $seoConfig->title = $paymentProcessor->name . " payment processor faucets (" . count($faucets) . ")";
        $seoConfig->description = "This page shows a list of faucets which use " . $paymentProcessor->name .
            " as a payment processor. There are currently " .
            count($faucets) . " faucets for this payment processor.";
        $seoConfig->keywords = $faucets->pluck('name')->toArray();
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('payment-processors.faucets', ['slug' => $paymentProcessor->slug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = 'Crypto Payment Processor Faucets';
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = Users::adminUser()->user_name .
            '-' . Users::adminUser()->id .
            '-payment-processors-' . $paymentProcessor->slug . '-faucets-index';

        return view('payment_processors.faucets.index')
            ->with('faucets', $faucets)
            ->with('paymentProcessor', $paymentProcessor)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier)
            ->with('message', $message);
    }

    /**
     * [userPaymentProcessors description]
     *
     * @param  $userSlug
     * @return \Illuminate\View\View
     */
    public function userPaymentProcessors($userSlug)
    {
        $user = null;
        $paymentProcessors = null;

        if (Auth::user() != null && Auth::user()->isAnAdmin()) {
            $user = User::withTrashed()->where('slug', $userSlug)->first();
            $paymentProcessors = $this->paymentProcessorRepository->withTrashed()->get();
        } else {
            $user = User::where('slug', $userSlug)->first();
            $paymentProcessors = $this->paymentProcessorRepository->all();
        }

        if (empty($user)) {
            flash('That user was not found.')->error();
            return redirect(route('users.index'));
        }

        if ($user->isAnAdmin()) {
            return redirect(route('payment-processors.index'));
        }

        $seoConfig = new SeoConfig();
        $seoConfig->title = "Crypto payment processors for " . $user->user_name;
        $seoConfig->description = "Here are " . $user->user_name . "'s faucets linked by system payment processors.";
        $seoConfig->keywords = $paymentProcessors->pluck('name')->toArray();
        $seoConfig->publishedTime = Carbon::now()->toW3CString();
        $seoConfig->modifiedTime = Carbon::now()->toW3CString();
        $seoConfig->authorName = $user->fullName();
        $seoConfig->currentUrl = route('users.payment-processors', ['userSlug' =>  $userSlug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = 'Crypto Payment Processors';
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = $user->user_name . '-' . $user->id . '-payment-processors-list';

        return view('users.payment_processors.index')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('user', $user)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Gets standard user faucets associated with a particular payment processor.
     *
     * @param  $userSlug
     * @param  $paymentProcessorSlug
     * @return \Illuminate\View\View
     */
    public function userPaymentProcessorFaucets($userSlug, $paymentProcessorSlug)
    {
        $user = null;
        $faucets = null;
        $paymentProcessor = null;

        if (Auth::user() != null && Auth::user()->isAnAdmin()) {
            $user = User::withTrashed()->where('slug', $userSlug)->first();
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $paymentProcessorSlug, true)->first();
        } else {
            $user = User::where('slug', $userSlug)->first();
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $paymentProcessorSlug)->first();
        }

        if (empty($user)) {
            flash('That user not found.')->error();
            return redirect(route('users.index'));
        }

        if (empty($paymentProcessor)) {
            flash("The payment processor was not found.")->error();
            return redirect(route('users.payment-processors', $user->slug));
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        if ($user->isAnAdmin()) {
            return redirect(route('payment-processors.faucets', ['slug' => $paymentProcessor->slug]));
        }

        $keywords = array_map('trim', explode(',', $paymentProcessor->meta_keywords));

        $seoConfig = new SeoConfig();
        $seoConfig->title = $paymentProcessor->name . " faucets for " . $user->user_name . " (". count($faucets) . ")";
        $seoConfig->description = "Here are " . $user->user_name . "'s " . $paymentProcessor->name .
            " faucets. Right now, they have " . count($faucets) . " " .
            $paymentProcessor->name . " faucets.";
        $seoConfig->keywords = array_push($keywords, $paymentProcessor->name);
        $seoConfig->publishedTime = Carbon::now()->toW3CString();
        $seoConfig->modifiedTime = Carbon::now()->toW3CString();
        $seoConfig->authorName = $user->fullName();
        $seoConfig->currentUrl = route('users.payment-processors.faucets', ['userSlug' =>  $userSlug, 'paymentProcessorSlug' => $paymentProcessor->slug]);
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = 'Crypto Payment Processor Faucets';
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = $user->user_name . '-' . $user->id . '-payment-processors-list';

        return view('users.payment_processors.faucets.index')
            ->with('user', $user)
            ->with('faucets', $faucets)
            ->with('paymentProcessor', $paymentProcessor)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Show the form for editing the specified PaymentProcessor.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.edit', ['slug' => $slug], ['slug' => $slug]);
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, true)->first();

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('paymentProcessors.index'));
        }

        return view('payment_processors.edit')->with('paymentProcessor', $paymentProcessor);
    }

    /**
     * Update the specified PaymentProcessor in storage.
     *
     * @param string                        $slug
     * @param UpdatePaymentProcessorRequest $request
     *
     * @return Response
     */
    public function update($slug, UpdatePaymentProcessorRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.update', ['slug' => $slug], ['slug' => $slug]);
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, true)->first();

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->update($request->all(), $paymentProcessor->id);

        flash('The \'' . $paymentProcessor->name . '\' payment processor was updated successfully!')->success();

        activity(Constants::ADMIN_PAYMENT_PROCESSOR_LOG)
            ->performedOn($paymentProcessor)
            ->causedBy(Auth::user())
            ->log("The payment processor ':subject.name' was updated by :causer.user_name");

        return redirect(route('payment-processors.index'));
    }

    /**
     * Remove the specified PaymentProcessor from storage.
     *
     * @param string $slug
     *
     * @return Response
     */
    public function destroy($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.destroy', ['slug' => $slug], ['slug' => $slug]);
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
        //$logPaymentProcessor

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->deleteWhere(['slug' => $slug]);

        flash('The \'' . $paymentProcessor->name . '\' payment processor was archived/deleted successfully!')->success();

        activity(Constants::ADMIN_PAYMENT_PROCESSOR_LOG)
            ->performedOn($paymentProcessor)
            ->causedBy(Auth::user())
            ->log("The payment processor ':subject.name' was archived/deleted by :causer.user_name");

        return redirect(route('payment-processors.index'));
    }

    /**
     * Destroys a payment processor permanently.
     *
     * @param  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, true)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'payment-processors.delete-permanently',
            ['slug' => $slug],
            [
                'paymentProcessor' => $paymentProcessor,
                'slug' => $slug
            ]
        );

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $paymentProcessorName = $paymentProcessor->name;

        $paymentProcessor->forceDelete();

        flash('The \'' . $paymentProcessorName . '\' payment processor was permanently deleted!')->success();

        activity(Constants::ADMIN_PAYMENT_PROCESSOR_LOG)
            ->performedOn($paymentProcessor)
            ->causedBy(Auth::user())
            ->log("The payment processor ':subject.name' was deleted permanently by :causer.user_name");

        return redirect(route('payment-processors.index'));
    }

    /**
     * Restore a soft-deleted payment processor.
     *
     * @param  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug, true)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'payment-processors.restore',
            ['slug' => $slug],
            [
                'paymentProcessor' => $paymentProcessor,
                'slug' => $slug
            ]
        );

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->restoreDeleted($slug);

        flash('The \'' . $paymentProcessor->name . '\' payment processor was successfully restored!')->success();

        activity(Constants::ADMIN_PAYMENT_PROCESSOR_LOG)
            ->performedOn($paymentProcessor)
            ->causedBy(Auth::user())
            ->log("The payment processor ':subject.name' was restored by :causer.user_name");

        return redirect(route('payment-processors.index'));
    }
}
