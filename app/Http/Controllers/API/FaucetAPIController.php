<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFaucetAPIRequest;
use App\Http\Requests\API\UpdateFaucetAPIRequest;
use App\Models\Faucet;
use App\Repositories\FaucetRepository;
use App\Transformers\FaucetsTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class FaucetController
 * @package App\Http\Controllers\API
 */

class FaucetAPIController extends AppBaseController
{
    /** @var  FaucetRepository */
    private $faucetRepository;

    public function __construct(FaucetRepository $faucetRepo)
    {
        $this->faucetRepository = $faucetRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/faucets",
     *      summary="Get a listing of the Faucets.",
     *      tags={"Faucet"},
     *      description="Get all Faucets",
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
     *                  @SWG\Items(ref="#/definitions/Faucet")
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
        $this->faucetRepository->pushCriteria(new RequestCriteria($request));
        $this->faucetRepository->pushCriteria(new LimitOffsetCriteria($request));

        $faucets = $this->faucetRepository->all();

        for ($i = 0; $i < count($faucets); $i++) {
            $faucets[$i] = (new FaucetsTransformer)->transform($faucets[$i], true);
        }

        return $this->sendResponse($faucets, 'Faucets retrieved successfully');
    }

    /**
     * @param CreateFaucetAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/faucets",
     *      summary="Store a newly created Faucet in storage",
     *      tags={"Faucet"},
     *      description="Store Faucet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Faucet that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Faucet")
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
     *                  ref="#/definitions/Faucet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    /*public function store(CreateFaucetAPIRequest $request)
    {
        $input = $request->all();

        $faucets = $this->faucetRepository->create($input);

        return $this->sendResponse($faucets->toArray(), 'Faucet saved successfully');
    }*/

    /**
     * @param $slug
     * @return Response
     *
     * @SWG\Get(
     *      path="/faucets/{slug}",
     *      summary="Display the specified Faucet",
     *      tags={"Faucet"},
     *      description="Get Faucet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="slug",
     *          description="slug of Faucet",
     *          type="string",
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
     *                  ref="#/definitions/Faucet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($slug)
    {
        /** @var Faucet $faucet */
        $faucet = $this->faucetRepository->findByField('slug', $slug)->first();

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        return $this->sendResponse((new FaucetsTransformer)->transform($faucet, true), 'Faucet retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFaucetAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/faucets/{id}",
     *      summary="Update the specified Faucet in storage",
     *      tags={"Faucet"},
     *      description="Update Faucet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Faucet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Faucet that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Faucet")
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
     *                  ref="#/definitions/Faucet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFaucetAPIRequest $request)
    {
        $input = $request->all();

        /** @var Faucet $faucet */
        $faucet = $this->faucetRepository->findWithoutFail($id);

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        $faucet = $this->faucetRepository->update($input, $id);

        return $this->sendResponse($faucet->toArray(), 'Faucet updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/faucets/{id}",
     *      summary="Remove the specified Faucet from storage",
     *      tags={"Faucet"},
     *      description="Delete Faucet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Faucet",
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
    public function destroy($id)
    {
        /** @var Faucet $faucet */
        $faucet = $this->faucetRepository->findWithoutFail($id);

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        $faucet->delete();

        return $this->sendResponse($id, 'Faucet deleted successfully');
    }
}
