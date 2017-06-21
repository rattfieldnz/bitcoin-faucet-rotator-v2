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
 * @package App\Http\Controllers\API
 */

class PaymentProcessorAPIController extends AppBaseController
{
    /** @var  PaymentProcessorRepository */
    private $paymentProcessorRepository;

    public function __construct(PaymentProcessorRepository $paymentProcessorRepo)
    {
        $this->paymentProcessorRepository = $paymentProcessorRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Response
     *
     * @SWG\Get(
     *      path="/paymentProcessors",
     *      summary="Get a listing of the PaymentProcessors.",
     *      tags={"PaymentProcessor"},
     *      description="Get all PaymentProcessors",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/PaymentProcessor")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
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
     * @param CreatePaymentProcessorAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/paymentProcessors",
     *      summary="Store a newly created PaymentProcessor in storage",
     *      tags={"PaymentProcessor"},
     *      description="Store PaymentProcessor",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PaymentProcessor that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PaymentProcessor")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/PaymentProcessor"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    /*public function store(CreatePaymentProcessorAPIRequest $request)
    {
        $input = $request->all();

        $paymentProcessors = $this->paymentProcessorRepository->create($input);

        return $this->sendResponse($paymentProcessors->toArray(), 'Payment Processor saved successfully');
    }*/

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/paymentProcessors/{id}",
     *      summary="Display the specified PaymentProcessor",
     *      tags={"PaymentProcessor"},
     *      description="Get PaymentProcessor",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PaymentProcessor",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/PaymentProcessor"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findWithoutFail($id);

        if (empty($paymentProcessor)) {
            return $this->sendError('Payment Processor not found');
        }

        return $this->sendResponse($paymentProcessor->toArray(), 'Payment Processor retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePaymentProcessorAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/paymentProcessors/{id}",
     *      summary="Update the specified PaymentProcessor in storage",
     *      tags={"PaymentProcessor"},
     *      description="Update PaymentProcessor",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PaymentProcessor",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PaymentProcessor that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PaymentProcessor")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/PaymentProcessor"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    /*public function update($id, UpdatePaymentProcessorAPIRequest $request)
    {
        $input = $request->all();

        $paymentProcessor = $this->paymentProcessorRepository->findWithoutFail($id);

        if (empty($paymentProcessor)) {
            return $this->sendError('Payment Processor not found');
        }

        $paymentProcessor = $this->paymentProcessorRepository->update($input, $id);

        return $this->sendResponse($paymentProcessor->toArray(), 'PaymentProcessor updated successfully');
    }*/

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/paymentProcessors/{id}",
     *      summary="Remove the specified PaymentProcessor from storage",
     *      tags={"PaymentProcessor"},
     *      description="Delete PaymentProcessor",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of PaymentProcessor",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    /*public function destroy($id)
    {
        $paymentProcessor = $this->paymentProcessorRepository->findWithoutFail($id);

        if (empty($paymentProcessor)) {
            return $this->sendError('Payment Processor not found');
        }

        $paymentProcessor->delete();

        return $this->sendResponse($id, 'Payment Processor deleted successfully');
    }*/
}
