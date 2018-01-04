<?php

namespace App\Http\Controllers\API;

use App\Helpers\Functions\Faucets;
use App\Helpers\Functions\Http;
use App\Helpers\Functions\PaymentProcessors;
use App\Helpers\Functions\Users;
use App\Models\Faucet;
use App\Models\User;
use App\Repositories\FaucetRepository;
use App\Repositories\PaymentProcessorRepository;
use App\Transformers\FaucetsTransformer;
use Form;
use Html;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Yajra\DataTables\Facades\DataTables;

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
    private $paymentProcessorRepo;
    private $adminUser;

    public function __construct(FaucetRepository $faucetRepo, PaymentProcessorRepository $paymentProcessorRepo)
    {
        $this->faucetRepository = $faucetRepo;
        $deleted = Auth::check() && Auth::user()->isAnAdmin() ? true : false;
        $this->faucetCollection = $this->faucetRepository->findItemsWhere(
            ['is_paused' => false, 'has_low_balance' => false],
            ['*'],
            $deleted
        )->sortBy('interval_minutes')->values();

        $this->paymentProcessorRepo = $paymentProcessorRepo;
        $this->adminUser = Users::adminUser();
    }

    /* End points for main rotator */
    public function index()
    {

        $faucets = new Collection();

        for ($i = 0; $i < count($this->faucetCollection); $i++) {
            $data = Faucets::faucetData($this->faucetCollection[$i]);

            $faucets->push($data);
        }

        return Datatables::of($faucets)->rawColumns(['actions'])->make(true);
    }

    public function show($slug)
    {
        $faucet = $this->faucetRepository->findWhere(
            ['is_paused' => false, 'has_low_balance' => false, 'slug' => $slug, 'deleted_at' => null]
        )->first();

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        return Faucets::faucetJsonResponse($faucet, Users::adminUser());
    }

    public function getFirstFaucet()
    {
        $timedOutCount = 0;
        if (Http::urlTimeOut($this->faucetCollection[0]->url, 5) == false) {
            $timedOutCount += 1;
        }

        if (!empty($this->faucetCollection[$timedOutCount])) {
            return Faucets::faucetJsonResponse($this->faucetCollection[$timedOutCount], Users::adminUser());
        }
    }

    public function getPreviousFaucet($slug)
    {
        $faucetSlugs = array_column($this->faucetCollection->toArray(), 'slug');

        foreach ($faucetSlugs as $key => $value) {
            if ($value == $slug) {
                // Decrement key to find previous one.

                $timedOutCount = 0;

                if (Http::urlTimeOut($this->faucetCollection[count($faucetSlugs) - 1]->url, 5) == false) {
                    $timedOutCount += 1;
                }

                $prevSlug = $key - 1 < 0 ? $faucetSlugs[(count($faucetSlugs) - 1) - $timedOutCount] : $faucetSlugs[($key - 1) - $timedOutCount];

                $previousFaucet = $this->faucetRepository->findWhere(
                    [
                        'is_paused' => false,
                        'has_low_balance' => false,
                        'slug' => $prevSlug,
                        'deleted_at' => null
                    ]
                )->first();

                return Faucets::faucetJsonResponse($previousFaucet, Users::adminUser());
            }
        }
    }

    public function getNextFaucet($slug)
    {
        $faucetSlugs = array_column($this->faucetCollection->toArray(), 'slug');
        $nextFaucet = null;

        foreach ($faucetSlugs as $key => $value) {
            if ($value == $slug) {
                $timedOutCount = 0;

                // Increase key to find next one.
                if ($key + 1 > count($faucetSlugs) - 1) {
                    // If addition is greater than number of faucets,
                    // We are at end of the collection.
                    // Go to first faucet in the collection.

                    if (Http::urlTimeOut($this->faucetCollection[count($faucetSlugs) - 1]->url, 5) == false) {
                        $timedOutCount += 1;
                    }

                    if (!empty($faucetSlugs[$timedOutCount])) {
                        $nextFaucet = $this->faucetRepository->findWhere(
                            [
                                'is_paused' => false,
                                'has_low_balance' => false,
                                'slug' => $faucetSlugs[$timedOutCount],
                                'deleted_at' => null
                            ]
                        )->first();

                        return $this->sendResponse(
                            (new FaucetsTransformer)->transform(
                                $this->adminUser,
                                $nextFaucet,
                                true
                            ),
                            'Faucet retrieved successfully'
                        );
                    }
                }

                if (Http::urlTimeOut($this->faucetCollection[count($faucetSlugs) - 1]->url, 5) == false) {
                    $timedOutCount += 1;
                }

                if (!empty($faucetSlugs[($key + 1) + $timedOutCount])) {
                    $nextFaucet = $this->faucetRepository->findWhere(
                        [
                            'is_paused' => false,
                            'has_low_balance' => false,
                            'slug' => $faucetSlugs[($key + 1) + $timedOutCount],
                            'deleted_at' => null
                        ]
                    )->first();

                    return $this->sendResponse(
                        (new FaucetsTransformer)->transform(
                            $this->adminUser,
                            $nextFaucet,
                            true
                        ),
                        'Faucet retrieved successfully'
                    );
                }
            }
        }
        return null;
    }

    public function getLastFaucet()
    {
        return Faucets::faucetJsonResponse($this->faucetCollection[count($this->faucetCollection) - 1], Users::adminUser());
    }

    public function getRandomFaucet()
    {
        $faucets = $this->faucetCollection;

        $randomIndex = rand(0, count($faucets) - 1);

        return Faucets::faucetJsonResponse($faucets[$randomIndex], Users::adminUser());
    }

    /* End points for payment processor rotator */
    public function getPaymentProcessorFaucet($paymentProcessorSlug, $faucetSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucet = $paymentProcessor->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->where('faucets.slug', '=', $faucetSlug)
            ->first();

        return Faucets::faucetJsonResponse($faucet, Users::adminUser());
    }

    public function getPaymentProcessorFaucets($paymentProcessorSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        $formattedFaucets = new Collection();

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->orderBy('faucets.interval_minutes')
            ->get();

        for ($i = 0; $i < count($faucets); $i++) {
            $data = Faucets::faucetData($faucets[$i]);

            $formattedFaucets->push($data);
        }

        return Datatables::of($formattedFaucets)->rawColumns(['actions'])->make(true);
    }

    public function getFirstPaymentProcessorFaucet($paymentProcessorSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->orderBy('faucets.interval_minutes')
            ->get();

        return Faucets::faucetJsonResponse($faucets[0], Users::adminUser());
    }

    public function getPreviousPaymentProcessorFaucet($paymentProcessorSlug, $faucetSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->orderBy('faucets.interval_minutes');

        $array = array_column($faucets->get()->toArray(), 'slug');

        foreach ($array as $key => $value) {
            if ($value == $faucetSlug) {
                // Increase key to find next one.
                if ($key - 1 > count($array) - 1) {
                    // If addition is greater than number of faucets,
                    // We are at end of the collection.
                    // Go to first faucet in the collection.
                    $prevFaucetSlug = $array[0];
                } else {
                    $prevFaucetSlug = $array[$key - 1];
                }

                $faucet = Faucet::where('is_paused', '=', false)
                    ->where('has_low_balance', '=', false)
                    ->where('deleted_at', '=', null)
                    ->where('slug', '=', $prevFaucetSlug)
                    ->orderBy('interval_minutes')
                    ->first();

                return Faucets::faucetJsonResponse($faucet, Users::adminUser());
            }
        }
    }

    public function getNextPaymentProcessorFaucet($paymentProcessorSlug, $faucetSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->orderBy('faucets.interval_minutes');

        $array = array_column($faucets->get()->toArray(), 'slug');

        foreach ($array as $key => $value) {
            if ($value == $faucetSlug) {
                // Increase key to find next one.

                // Increase key to find next one.
                if ($key + 1 > count($array) - 1) {
                    // If addition is greater than number of faucets,
                    // We are at end of the collection.
                    // Go to first faucet in the collection.
                    $nextFaucetSlug = $array[0];
                } else {
                    $nextFaucetSlug = $array[$key + 1];
                }

                $faucet = Faucet::where('is_paused', '=', false)
                    ->where('has_low_balance', '=', false)
                    ->where('deleted_at', '=', null)
                    ->where('slug', '=', $nextFaucetSlug)
                    ->orderBy('interval_minutes')
                    ->first();

                return Faucets::faucetJsonResponse($faucet, Users::adminUser());
            }
        }
    }

    public function getLastPaymentProcessorFaucet($paymentProcessorSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->orderBy('faucets.interval_minutes')
            ->get();

        return Faucets::faucetJsonResponse($faucets[count($faucets) - 1], Users::adminUser());
    }

    public function getRandomPaymentProcessorFaucet($paymentProcessorSlug)
    {
        //Obtain payment processor by related slug.
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        // Use model relationship to obtain associated faucets
        $faucets = $paymentProcessor->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->orderBy('faucets.interval_minutes')
            ->get();

        $randomIndex = rand(0, count($faucets) - 1);

        return Faucets::faucetJsonResponse($faucets[$randomIndex], Users::adminUser());
    }

    /* End points for user's main rotator */
    public function getUserFaucets($userSlug)
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

        $showFaucets = $user->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null)
            ->where('referral_code', '!=', null)
            ->orderBy('faucets.interval_minutes')->get();

        $mainFaucets = Auth::check() && Auth::user()->isAnAdmin() ?
            Faucet::withTrashed()->get() :
            Faucet::withoutTrashed()->get();

        $userFaucets = Auth::check() && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id) ?
            $mainFaucets : $showFaucets;

        $faucets = new Collection();

        for ($i = 0; $i < count($userFaucets); $i++) {
            $referralCode = Faucets::getUserFaucetRefCode($user, $userFaucets[$i]);

            $faucetRoute = !empty(Users::getFaucet($user, $userFaucets[$i]->slug)) ? route(
                'users.faucets.show',
                ['userSlug' => $user->slug, 'faucetSlug' => $userFaucets[$i]->slug]
            ) : route(
                'faucets.show',
                ['slug' => $userFaucets[$i]->slug]
            );

            $data = [
                'name' => [
                    'display' => $faucetRoute,
                    'original' => $userFaucets[$i]->name,
                ],
                'url' => $userFaucets[$i]->url . $referralCode,
                'referral_code' => $referralCode,
                'interval_minutes' => intval($userFaucets[$i]->interval_minutes),
                'min_payout' => [
                    'display' => number_format(intval($userFaucets[$i]->min_payout)),
                    'original' => intval($userFaucets[$i]->min_payout)
                ],
                'max_payout' => [
                    'display' => number_format(intval($userFaucets[$i]->max_payout)),
                    'original' => intval($userFaucets[$i]->max_payout)
                ],
                'comments' => $userFaucets[$i]->comments,
                'is_paused' => [
                    'display' => $userFaucets[$i]->is_paused == true ? "Yes" : "No",
                    'original' => $userFaucets[$i]->is_paused
                ],
                'slug' => $userFaucets[$i]->slug,
                'has_low_balance' => $userFaucets[$i]->has_low_balance,
            ];

            $paymentProcessors = $userFaucets[$i]->paymentProcessors()->get();

            if (count($paymentProcessors) != 0) {
                $data['payment_processors'] = [];
                foreach ($paymentProcessors as $p) {
                    array_push(
                        $data['payment_processors'],
                        [
                            'name' => $p->name,
                            'url' => route(
                                'users.payment-processors.faucets',
                                ['userSlug' => $user->slug, 'paymentProcessorSlug' => $p->slug]
                            )
                        ]
                    );
                }
            }
            if (Auth::check() && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
                $data['id'] = intval($userFaucets[$i]->id);

                $data['referral_code_form'] = Form::hidden('faucet_id[]', $userFaucets[$i]->id) .
                    Form::text(
                        'referral_code[]',
                        Faucets::getUserFaucetRefCode($user, $userFaucets[$i]),
                        ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']
                    );
            }

            $faucets->push($data);
        }

        return Datatables::of($faucets)->rawColumns(['referral_code_form'])->make(true);
    }

    public function getUserFaucet($userSlug, $faucetSlug)
    {

        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucet = Users::getFaucet($user, $faucetSlug);

        return Faucets::faucetJsonResponse($faucet, $user);
    }

    public function getFirstUserFaucet($userSlug)
    {

        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucets = new Collection();

        $userFaucets = Users::getFaucets($user);

        foreach ($userFaucets as $f) {
            if (!empty($f->pivot->referral_code)) {
                $faucets->push($f);
            }
        }

        return Faucets::faucetJsonResponse($faucets->first(), $user);
    }

    public function getPreviousUserFaucet($userSlug, $faucetSlug)
    {

        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucet = $this->faucetRepository->findWhere(['slug' => $faucetSlug])->first();

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        $faucets = new Collection();

        $userFaucets = Users::getFaucets($user);

        foreach ($userFaucets as $f) {
            if (!empty($f->pivot->referral_code)) {
                $faucets->push($f);
            }
        }

        $array = array_column($faucets->toArray(), 'slug');

        foreach ($array as $key => $value) {
            if ($value == $faucetSlug) {
                // Increase key to find next one.
                // If addition is greater than number of faucets,
                // We are at end of the collection.
                // Go to first faucet in the collection.
                $faucetData = ($key - 1 > count($array) - 1) ? $array[0] : ($array[($key - 1) < 0 ? count($array) - $key - 1 : $key - 1]);

                $faucet = Users::getFaucet($user, $faucetData);

                return Faucets::faucetJsonResponse($faucet, $user);
            }
        }
    }

    public function getNextUserFaucet($userSlug, $faucetSlug)
    {

        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucet = $this->faucetRepository->findWhere(['slug' => $faucetSlug])->first();

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        $userFaucets = $user->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->wherePivot('referral_code', '!=', null)
            ->orderBy('faucets.interval_minutes')
            ->get();

        $array = array_column($userFaucets->toArray(), 'slug');

        foreach ($array as $key => $value) {
            if ($value == $faucetSlug) {
                // Increase key to find next one.
                if ($key + 1 > count($array) - 1) {
                    // If addition is greater than number of faucets,
                    // We are at end of the collection.
                    // Go to first faucet in the collection.
                    $nextFaucetSlug = $array[0];
                } else {
                    $nextFaucetSlug = $array[$key + 1];
                }

                $faucet = $user->faucets()
                    ->where('faucets.is_paused', '=', false)
                    ->where('faucets.has_low_balance', '=', false)
                    ->where('faucets.slug', '=', $nextFaucetSlug)
                    ->wherePivot('referral_code', '!=', null)
                    ->orderBy('faucets.interval_minutes')
                    ->first();

                return Faucets::faucetJsonResponse($faucet, $user);
            }
        }
    }

    public function getLastUserFaucet($userSlug)
    {
        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucets = new Collection();

        foreach (Users::getFaucets($user) as $f) {
            if (!empty($f->pivot->referral_code)) {
                $faucets->push($f);
            }
        }

        $userFaucet = (new FaucetsTransformer)->transform($user, $faucets[count($faucets) - 1], true);

        return $this->sendResponse($userFaucet, 'User faucet retrieved successfully');
    }

    public function getRandomUserFaucet($userSlug)
    {
        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        $faucets = new Collection();

        foreach (Users::getFaucets($user) as $f) {
            if (!empty($f->pivot->referral_code)) {
                $faucets->push($f);
            }
        }

        $randomIndex = rand(0, count($faucets) - 1);

        $userFaucet = (new FaucetsTransformer)->transform($user, $faucets[$randomIndex], false);

        return $this->sendResponse($userFaucet, 'User faucet retrieved successfully');
    }

    /* End points for user's payment processor rotator */
    public function getUserPaymentProcessorFaucets($userSlug, $paymentProcessorSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getPaymentProcessorFaucets($paymentProcessor->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        $paymentProcessorFaucets = new Collection();

        for ($i = 0; $i < count($faucets); $i++) {
            if (!empty($faucets[$i])) {
                $data = Faucets::userFaucetData($faucets[$i], $user);
                $paymentProcessorFaucets->push($data);
            }
        }

        return Datatables::of($paymentProcessorFaucets)->rawColumns(['referral_code_form'])->make(true);
    }

    public function getUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug, $faucetSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();
        $faucet = $this->faucetRepository->findWhere(['slug' => $faucetSlug])->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getPaymentProcessorFaucet($paymentProcessor->slug, $faucet->slug);
        }

        $userFaucet = PaymentProcessors::userPaymentProcessorFaucet($user, $paymentProcessor, $faucet);

        $userFaucet = (new FaucetsTransformer)->transform($user, $userFaucet, true);

        return $this->sendResponse($userFaucet, 'User payment processor faucet retrieved successfully');
    }

    public function getFirstUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getFirstPaymentProcessorFaucet($paymentProcessor->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        if (count($faucets->all()) == 0) {
            $errorMessage = 'The first faucet does not exist, or the user has no faucets using the "' . $paymentProcessor->name . '" payment processor.';
            return $this->sendResponse(
                [
                    'status' => 'error',
                    'code' => 404,
                    'message' => $errorMessage
                ],
                $errorMessage
            );
        }

        $faucet = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor)->first();

        $faucet = (new FaucetsTransformer)->transform($user, $faucet, true);

        return $this->sendResponse($faucet, 'User payment processor faucet retrieved successfully');
    }

    public function getNextUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug, $faucetSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();
        $faucet = $this->faucetRepository->findWhere(['slug' => $faucetSlug])->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getNextPaymentProcessorFaucet($paymentProcessor->slug, $faucet->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        $array = array_column($faucets->toArray(), 'slug');

        foreach ($array as $key => $value) {
            if ($value == $faucet->slug) {
                // Increase key to find next one.
                // If addition is greater than number of faucets,
                // We are at end of the collection.
                // Go to first faucet in the collection.
                $faucetData = ($key + 1 > count($array) - 1) ? $array[0] : $array[$key + 1];

                $faucet = Users::getFaucet($user, $faucetData);

                return Faucets::faucetJsonResponse($faucet, $user);
            }
        }
    }

    public function getPreviousUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug, $faucetSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();
        $faucet = $this->faucetRepository->findWhere(['slug' => $faucetSlug])->first();

        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if (empty($faucet)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'],
                "Faucet not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getNextPaymentProcessorFaucet($paymentProcessor->slug, $faucet->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        $array = array_column($faucets->toArray(), 'slug');
        foreach ($array as $key => $value) {
            if ($value == $faucet->slug) {
                // Decrease key to find next one.
                // If key is greater than number of faucets,
                // We are at end of the collection.
                // Go to first faucet in the collection.
                $faucetData = ($key - 1 > count($array) - 1) ? $array[0] : ($array[($key - 1) < 0 ? count($array) - $key - 1 : $key - 1]);

                $faucet = Users::getFaucet($user, $faucetData);

                return Faucets::faucetJsonResponse($faucet, $user);
            }
        }
    }

    public function getLastUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();
        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getLastPaymentProcessorFaucet($paymentProcessor->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        $userFaucet = (new FaucetsTransformer)->transform($user, $faucets[count($faucets) - 1], true);

        return $this->sendResponse($userFaucet, 'User payment processor faucet retrieved successfully');
    }

    public function getRandomUserPaymentProcessorFaucet($userSlug, $paymentProcessorSlug)
    {
        $paymentProcessor = $this->paymentProcessorRepo->findWhere(['slug' => $paymentProcessorSlug])->first();
        $user = User::where('slug', '=', $userSlug)->first();
        if (empty($user)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'User not found.'],
                "User not found."
            );
        }

        if (empty($paymentProcessor)) {
            return $this->sendResponse(
                ['status' => 'error', 'code' => 404, 'message' => 'Payment processor not found.'],
                "Payment processor not found."
            );
        }

        if ($user->isAnAdmin()) {
            return $this->getRandomPaymentProcessorFaucet($paymentProcessor->slug);
        }

        $faucets = PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor);

        $randomIndex = rand(0, count($faucets) - 1);

        $userFaucet = (new FaucetsTransformer)->transform($user, $faucets[$randomIndex], false);

        return $this->sendResponse($userFaucet, 'User payment processor faucet retrieved successfully');
    }
}
