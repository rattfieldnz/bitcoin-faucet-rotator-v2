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
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\API
 */

class FaucetAPIController extends AppBaseController
{
    /**
 * @var  FaucetRepository
*/
    private $faucetRepository;

    public function __construct(FaucetRepository $faucetRepo)
    {
        $this->faucetRepository = $faucetRepo;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse *
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

    /*public function store(CreateFaucetAPIRequest $request)
    {
        $input = $request->all();

        $faucets = $this->faucetRepository->create($input);

        return $this->sendResponse($faucets->toArray(), 'Faucet saved successfully');
    }*/

    public function show($slug)
    {
        /**
 * @var Faucet $faucet
*/
        $faucet = $this->faucetRepository->findByField('slug', $slug)->first();

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        return $this->sendResponse((new FaucetsTransformer)->transform($faucet, true), 'Faucet retrieved successfully');
    }

    /*public function update($id, UpdateFaucetAPIRequest $request)
    {
        $input = $request->all();

        $faucet = $this->faucetRepository->findWithoutFail($id);

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        $faucet = $this->faucetRepository->update($input, $id);

        return $this->sendResponse($faucet->toArray(), 'Faucet updated successfully');
    }*/

    /*public function destroy($id)
    {
        $faucet = $this->faucetRepository->findWithoutFail($id);

        if (empty($faucet)) {
            return $this->sendError('Faucet not found');
        }

        $faucet->delete();

        return $this->sendResponse($id, 'Faucet deleted successfully');
    }*/
}
