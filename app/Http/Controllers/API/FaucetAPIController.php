<?php

namespace App\Http\Controllers\API;

use App\Helpers\Functions\Faucets;
use App\Http\Requests\API\CreateFaucetAPIRequest;
use App\Http\Requests\API\UpdateFaucetAPIRequest;
use App\Models\Faucet;
use App\Repositories\FaucetRepository;
use App\Transformers\FaucetsTransformer;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Config;
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
    private $faucetCollection;

    public function __construct(FaucetRepository $faucetRepo)
    {
        $this->faucetRepository = $faucetRepo;
        $this->faucetCollection = $this->faucetRepository->findWhere(
            ['is_paused' => false, 'has_low_balance' => false]
        )->sortBy('interval_minutes')->values();
    }

    public function index(Request $request)
    {
        $this->faucetRepository->pushCriteria(new RequestCriteria($request));
        $this->faucetRepository->pushCriteria(new LimitOffsetCriteria($request));

        for ($i = 0; $i < count($this->faucetCollection); $i++) {
            $this->faucetCollection[$i] = (new FaucetsTransformer)
                ->transform($this->faucetCollection[$i], true);
        }

        return $this->sendResponse($this->faucetCollection, 'Faucets retrieved successfully');
    }

    public function show($slug)
    {

        $faucet = $this->faucetRepository->findWhere(
            ['is_paused' => false, 'has_low_balance' => false, 'slug' => $slug]
        )->first();

        if (empty($faucet)) {
            return $this->sendError('Faucet not found',404);
        }

        return $this->sendResponse(
            (new FaucetsTransformer)->transform(
                $faucet,
                    true
            ), 'Faucet retrieved successfully');
    }

    public function getFirstFaucet(){

        return $this->sendResponse(
            (new FaucetsTransformer)->transform(
                $this->faucetCollection[0],
                    true
            ), 'Faucet retrieved successfully');
    }

    public function getPreviousFaucet($slug){
        $faucetSlugs = array_column($this->faucetCollection->toArray(), 'slug');
        $previousFaucet = null;

        foreach($faucetSlugs as $key => $value){
            if($value == $slug){
                // Decrement key to find previous one.
                if($key - 1 < 0){
                    // If subtracted value is negative,
                    // we are at beginning of faucet collection array.
                    // Go to last faucet in the collection.
                    $previousFaucet = $this->faucetRepository->findWhere(
                        ['is_paused' => false, 'has_low_balance' => false, 'slug' => $faucetSlugs[count($faucetSlugs) - 1]]
                    )->first();

                    return $this->sendResponse(
                        (new FaucetsTransformer)->transform(
                            $previousFaucet,
                                true
                        ), 'Faucet retrieved successfully');
                }

                $previousFaucet = $this->faucetRepository->findWhere(
                    ['is_paused' => false, 'has_low_balance' => false, 'slug' => $faucetSlugs[$key - 1]]
                )->first();

                return $this->sendResponse(
                    (new FaucetsTransformer)->transform(
                        $previousFaucet,
                        true
                    ), 'Faucet retrieved successfully');
            }
        }
        return null;
    }

    public function getNextFaucet($slug){
        $faucetSlugs = array_column($this->faucetCollection->toArray(), 'slug');
        $nextFaucet = null;

        foreach($faucetSlugs as $key => $value){
            if($value == $slug){
                // Increase key to find next one.
                if($key + 1 > count($faucetSlugs) - 1){
                    // If addition is greater than number of faucets,
                    // We are at end of the collection.
                    // Go to first faucet in the collection.
                    $nextFaucet = $this->faucetRepository->findWhere(
                        ['is_paused' => false, 'has_low_balance' => false, 'slug' => $faucetSlugs[0]]
                    )->first();

                    return $this->sendResponse(
                        (new FaucetsTransformer)->transform(
                            $nextFaucet,
                            true
                        ), 'Faucet retrieved successfully');
                }
                $nextFaucet = $this->faucetRepository->findWhere(
                    ['is_paused' => false, 'has_low_balance' => false, 'slug' => $faucetSlugs[$key + 1]]
                )->first();

                return $this->sendResponse(
                    (new FaucetsTransformer)->transform(
                        $nextFaucet,
                        true
                    ), 'Faucet retrieved successfully');
            }
        }
        return null;
    }

    public function getLastFaucet(){

        return $this->sendResponse(
            (new FaucetsTransformer)->transform(
                $this->faucetCollection[count($this->faucetCollection) - 1],
                true
            ), 'Faucet retrieved successfully');
    }
}
