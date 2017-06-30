<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreatePaymentProcessorRequest;
use App\Http\Requests\UpdatePaymentProcessorRequest;
use App\Repositories\PaymentProcessorRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentProcessor;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Helpers\Functions\Users;
use Laracasts\Flash\Flash as LaracastsFlash;

class PaymentProcessorController extends AppBaseController
{
    /** @var  PaymentProcessorRepository */
    private $paymentProcessorRepository;
    private $userFunctions;

    public function __construct(PaymentProcessorRepository $paymentProcessorRepo, Users $userFunctions)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepo;
        $this->userFunctions = $userFunctions;
        $this->middleware('auth', ['except' => ['index', 'show', 'faucets', 'userPaymentProcessors', 'userPaymentProcessorFaucets']]);
    }

    /**
     * Display a listing of the PaymentProcessor.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->paymentProcessorRepository->pushCriteria(new RequestCriteria($request));
        $paymentProcessors = $this->paymentProcessorRepository->withTrashed()->get();

        return view('payment_processors.index')
            ->with('paymentProcessors', $paymentProcessors);
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
        if(Auth::user()->isAnAdmin()){
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first()->withTrashed()->first();
        } else{
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
        }

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        return view('payment_processors.show')->with('paymentProcessor', $paymentProcessor);
    }

    /**
     * Show main faucets associated with a payment processor.
     *
     * @param $slug
     * @return PaymentProcessorController|\Illuminate\View\View
     */
    public function faucets($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
        $faucets = null;

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        if (Auth::guest() || Auth::user()->hasRole('user')) {
            $faucets = $paymentProcessor->faucets()->get();
        } elseif (Auth::user()->hasRole('owner')) {
            $faucets = $paymentProcessor->faucets()->withTrashed()->get();
        }

        return view('payment_processors.faucets.index')
            ->with('faucets', $faucets)
            ->with('paymentProcessor', $paymentProcessor);
    }

    /**
     * [userPaymentProcessors description]
     * @param $userSlug
     * @return \Illuminate\View\View
     */
    public function userPaymentProcessors($userSlug)
    {
        $user = null;
        $paymentProcessors = null;

        if(Auth::user() != null && Auth::user()->isAnAdmin()) {
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

        return view('users.payment_processors.index')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('user', $user);
    }

    /**
     * Gets standard user faucets associated with a particular payment processor.
     *
     * @param $userSlug
     * @param $paymentProcessorSlug
     * @return \Illuminate\View\View
     */
    public function userPaymentProcessorFaucets($userSlug, $paymentProcessorSlug)
    {
        $user = null;
        $faucets = null;
        $paymentProcessor = null;

        if(Auth::user() != null && Auth::user()->isAnAdmin()) {
            $user = User::withTrashed()->where('slug', $userSlug)->first();
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $paymentProcessorSlug, true)->first();
        } else {
            $user = User::where('slug', $userSlug)->first();
            $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $paymentProcessorSlug)->first();
        }

        if(empty($user)) {
            flash('That user not found.')->error();
            return redirect(route('users.index'));
        }

        if(empty($paymentProcessor)) {
            flash("The payment processor was not found.")->error();
            return redirect(route('users.payment-processors', $user->slug));
        }

        if (Auth::guest()) {
            $faucets = $this->userFunctions->getPaymentProcessorFaucets($user, $paymentProcessor, false);
        } elseif (Auth::user() != null && (Auth::user()->isAnAdmin() || $user == Auth::user())) {
            $faucets = $this->userFunctions->getPaymentProcessorFaucets($user, $paymentProcessor, true);
        }

        return view('users.payment_processors.faucets.index')
            ->with('user', $user)
            ->with('faucets', $faucets)
            ->with('paymentProcessor', $paymentProcessor);
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
     * @param string $slug
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

        if (empty($paymentProcessor)) {
            flash('Payment Processor not found.')->error();

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->deleteWhere(['slug' => $slug]);

        flash('The \'' . $paymentProcessor->name . '\' payment processor was archived/deleted successfully!')->success();

        return redirect(route('payment-processors.index'));
    }

    /**
     * Destroys a payment processor permanently.
     *
     * @param $slug
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

        return redirect(route('payment-processors.index'));
    }

    /**
     * Restore a soft-deleted payment processor.
     *
     * @param $slug
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

        return redirect(route('payment-processors.index'));
    }
}
