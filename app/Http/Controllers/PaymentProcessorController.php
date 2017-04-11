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
     * @return Response
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
     * @return Response
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.create', null, null);
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

        Flash::success('Payment Processor saved successfully.');

        return redirect(route('payment-processors.index'));
    }

    /**
     * Display the specified PaymentProcessor.
     *
     * @param string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('payment-processors.index'));
        }

        return view('payment_processors.show')->with('paymentProcessor', $paymentProcessor);
    }

    public function faucets($slug){
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
        $faucets = null;

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('payment-processors.index'));
        }

        if(Auth::guest() || Auth::user()->hasRole('user')){
            $faucets = $paymentProcessor->faucets()->get();
        }
        else if(Auth::user()->hasRole('owner')){
            $faucets = $paymentProcessor->faucets()->withTrashed()->get();
        }

        return view('payment_processors.faucets.index')
            ->with('faucets', $faucets)
            ->with('paymentProcessor', $paymentProcessor);

    }

    /**
     * [userPaymentProcessors description]
     * @param  [type] $userSlug [description]
     * @return [type]           [description]
     */
    public function userPaymentProcessors($userSlug){

        $user = User::where('slug', $userSlug)->first();

        if(empty($user)){
            abort(404);
        }

        $paymentProcessors = $this->paymentProcessorRepository->withTrashed()->get();

        return view('users.payment_processors.index')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('user', $user);
    }

    public function userPaymentProcessorFaucets($userSlug, $paymentProcessorSlug){

        $user = User::where('slug', $userSlug)->first();
        $paymentProcessor = PaymentProcessor::where('slug', $paymentProcessorSlug)->first();

        $faucets = null;

        if(Auth::guest()){
            $faucets = $this->userFunctions->getPaymentProcessorFaucets($user, $paymentProcessor, false);
        }
        else if(Auth::user()->hasRole('user') || Auth::user()->hasRole('owner')){
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
     * @return Response
     */
    public function edit($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'payment-processors.edit',['slug' => $slug], ['slug' => $slug]);
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

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
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->update($request->all(), $paymentProcessor->id);

        Flash::success('Payment Processor updated successfully.');

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
            Flash::error('Payment Processor not found');

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->deleteWhere(['slug' => $slug]);

        Flash::success('Payment Processor deleted successfully.');

        return redirect(route('payment-processors.index'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
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

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->deleteWhere(['slug' => $slug], true);

        Flash::success('Payment Processor was permanently deleted!');

        return redirect(route('payment-processors.index'));

    }

    /**
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug){
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();
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

            return redirect(route('payment-processors.index'));
        }

        $this->paymentProcessorRepository->restoreDeleted($slug);

        Flash::success('Payment Processor was successfully restored!');

        return redirect(route('payment-processors.index'));

    }
}
