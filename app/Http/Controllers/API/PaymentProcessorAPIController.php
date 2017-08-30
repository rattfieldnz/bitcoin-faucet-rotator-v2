<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePaymentProcessorAPIRequest;
use App\Http\Requests\API\UpdatePaymentProcessorAPIRequest;
use App\Models\PaymentProcessor;
use App\Repositories\PaymentProcessorRepository;
use App\Transformers\PaymentProcessorsTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PaymentProcessorController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\API
 */

class PaymentProcessorAPIController extends AppBaseController
{
    private $paymentProcessorRepository;

    /**
     * PaymentProcessorAPIController constructor.
     *
     * @param \App\Repositories\PaymentProcessorRepository $paymentProcessorRepo
     */
    public function __construct(PaymentProcessorRepository $paymentProcessorRepo)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepo;
    }

    /**
     * Return JSON with payment processors and associated faucets.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->paymentProcessorRepository->pushCriteria(new RequestCriteria($request));
        $this->paymentProcessorRepository->pushCriteria(new LimitOffsetCriteria($request));

        $paymentProcessors = $this->paymentProcessorRepository->all();

        for ($i = 0; $i < count($paymentProcessors); $i++) {
            $paymentProcessors[$i] = (new PaymentProcessorsTransformer)->transform($paymentProcessors[$i], true);
        }

        return $this->sendResponse($paymentProcessors, 'Payment Processors retrieved successfully');
    }

    /**
     * Get a payment processor as JSON.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findWhere(['slug' => $slug])->first();
        
        if (empty($paymentProcessor)) {
            return $this->sendError('Payment Processor not found');
        }

        $paymentProcessor = (new PaymentProcessorsTransformer)->transform($paymentProcessor, true);

        return $this->sendResponse($paymentProcessor, 'Payment Processor retrieved successfully');
    }
}
