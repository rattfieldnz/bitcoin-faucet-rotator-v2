<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentProcessorRequest;
use App\Http\Requests\UpdatePaymentProcessorRequest;
use App\Repositories\PaymentProcessorRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PaymentProcessorController extends AppBaseController
{
    /** @var  PaymentProcessorRepository */
    private $paymentProcessorRepository;

    public function __construct(PaymentProcessorRepository $paymentProcessorRepo)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepo;
        $this->middleware('auth', ['except' => ['index', 'show']]);
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
        $paymentProcessors = $this->paymentProcessorRepository->all();

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
        return view('payment_processors.create');
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
        $input = $request->all();

        $paymentProcessor = $this->paymentProcessorRepository->create($input);

        Flash::success('Payment Processor saved successfully.');

        return redirect(route('payment-processors.index'));
    }

    /**
     * Display the specified PaymentProcessor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('paymentProcessors.index'));
        }

        return view('payment_processors.show')->with('paymentProcessor', $paymentProcessor);
    }

    /**
     * Show the form for editing the specified PaymentProcessor.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($slug)
    {
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
     * @param  int              $id
     * @param UpdatePaymentProcessorRequest $request
     *
     * @return Response
     */
    public function update($slug, UpdatePaymentProcessorRequest $request)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('paymentProcessors.index'));
        }

        $paymentProcessor = $this->paymentProcessorRepository->update($request->all(), $slug);

        Flash::success('Payment Processor updated successfully.');

        return redirect(route('paymentProcessors.index'));
    }

    /**
     * Remove the specified PaymentProcessor from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findByField('slug', $slug)->first();

        if (empty($paymentProcessor)) {
            Flash::error('Payment Processor not found');

            return redirect(route('paymentProcessors.index'));
        }

        $this->paymentProcessorRepository->deleteWhere(['slug' => $slug]);

        Flash::success('Payment Processor deleted successfully.');

        return redirect(route('paymentProcessors.index'));
    }
}
