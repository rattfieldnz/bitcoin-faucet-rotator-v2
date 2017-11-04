<?php

namespace App\Http\Controllers\API;

use App\Helpers\Functions\PaymentProcessors;
use App\Helpers\Functions\Users;
use App\Models\User;
use App\Repositories\PaymentProcessorRepository;
use App\Transformers\PaymentProcessorsTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Yajra\DataTables\Facades\DataTables;

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
     * @return \Illuminate\Http\JsonResponse
     * @internal param \Illuminate\Http\Request $request
     *
     */
    public function index()
    {
        $paymentProcessors = $this->paymentProcessorRepository->findItemsWhere([], ['*'], false);
        $formattedData = new Collection();

        for ($i = 0; $i < count($paymentProcessors); $i++) {
            $paymentProcessorFaucets = $paymentProcessors[$i]->faucets()->where('deleted_at', '=', null);
            $data = [
                'name' => [
                    'display' => route('payment-processors.show', ['slug' => $paymentProcessors[$i]->slug]),
                    'original' => $paymentProcessors[$i]->name,
                ],
                'faucets' => [
                    'display' => route('payment-processors.faucets', ['slug' => $paymentProcessors[$i]->slug]),
                    'original' => $paymentProcessors[$i]->name . ' Faucets'
                ],
                'rotator' => [
                    'display' => route('payment-processors.rotator', ['slug' => $paymentProcessors[$i]->slug]),
                    'original' => $paymentProcessors[$i]->name . ' Rotator'
                ],
                'no_of_faucets' => count($paymentProcessorFaucets->get()),
                'min_claimable' => [
                    'display' => number_format($paymentProcessorFaucets->sum('min_payout')) .
                        ' Satoshis / ' . number_format($paymentProcessorFaucets->sum('interval_minutes')) . ' minutes',
                    'original' => intval($paymentProcessorFaucets->sum('min_payout'))
                ],
                'max_claimable' => [
                    'display' => number_format($paymentProcessorFaucets->sum('max_payout')) .
                        ' Satoshis / ' . number_format($paymentProcessorFaucets->sum('interval_minutes')) . ' minutes',
                    'original' => intval($paymentProcessorFaucets->sum('max_payout'))
                ]
            ];

            if (Auth::check() && Auth::user()->isAnAdmin()) {
                $data['id'] = intval($paymentProcessors[$i]->id);
                $data['actions'] = '';
                $data['actions'] .= PaymentProcessors::htmlEditButton($paymentProcessors[$i]);


                if ($paymentProcessors[$i]->isDeleted()) {
                    $data['actions'] .= PaymentProcessors::deletePermanentlyForm($paymentProcessors[$i]);
                    $data['actions'] .= PaymentProcessors::restoreForm($paymentProcessors[$i]);
                }

                $data['actions'] .= PaymentProcessors::softDeleteForm($paymentProcessors[$i]);
            }
            $formattedData->push($data);
        }

        return Datatables::of($formattedData)->rawColumns(['actions'])->make(true);
    }

    /**
     * @param $userSlug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPaymentProcessors($userSlug)
    {
        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->index();
        }

        $paymentProcessors = $this->paymentProcessorRepository->findItemsWhere([], ['*'], false);
        $formattedData = new Collection();

        for ($i = 0; $i < count($paymentProcessors); $i++) {
            $paymentProcessorFaucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessors[$i]);
            $faucetsRoute = route(
                'users.payment-processors.faucets',
                [
                    'userSlug' => $user->slug,
                    'paymentProcessorSlug' => $paymentProcessors[$i]->slug
                ]
            );

            $data = [
                'name' => [
                    'display' => $faucetsRoute,
                    'original' => $paymentProcessors[$i]->name
                ],
                'faucets' => [
                    'display' => $faucetsRoute,
                    'original' => $paymentProcessors[$i]->name . ' Faucets'
                ],
                'rotator' => [
                    'display' => route(
                        'users.payment-processors.rotator',
                        [
                            'userSlug' => $user->slug,
                            'paymentProcessorSlug' => $paymentProcessors[$i]->slug
                        ]
                    ),
                    'original' => $paymentProcessors[$i]->name . ' Rotator'
                ],
                'no_of_faucets' => count($paymentProcessorFaucets->all()),
                'min_claimable' => [
                    'display' => number_format($paymentProcessorFaucets->sum('min_payout')) .
                        ' Satoshis / ' . number_format($paymentProcessorFaucets->sum('interval_minutes')) . ' minutes',
                    'original' => intval($paymentProcessorFaucets->sum('min_payout'))
                ],
                'max_claimable' => [
                    'display' => number_format($paymentProcessorFaucets->sum('max_payout')) .
                        ' Satoshis / ' . number_format($paymentProcessorFaucets->sum('interval_minutes')) . ' minutes',
                    'original' => intval($paymentProcessorFaucets->sum('max_payout'))
                ]
            ];

            if (Auth::check() && Auth::user()->isAnAdmin()) {
                $data['id'] = intval($paymentProcessors[$i]->id);
                $data['actions'] = '';
                $data['actions'] .= PaymentProcessors::htmlEditButton($paymentProcessors[$i]);


                if ($paymentProcessors[$i]->isDeleted()) {
                    $data['actions'] .= PaymentProcessors::deletePermanentlyForm($paymentProcessors[$i]);
                    $data['actions'] .= PaymentProcessors::restoreForm($paymentProcessors[$i]);
                }

                $data['actions'] .= PaymentProcessors::softDeleteForm($paymentProcessors[$i]);
            }
            $formattedData->push($data);
        }

        return Datatables::of($formattedData)->rawColumns(['actions'])->make(true);
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
