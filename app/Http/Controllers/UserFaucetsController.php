<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateUserFaucetRequest;
use App\Http\Requests\UpdateUserFaucetRequest;
use App\Models\Faucet;
use App\Models\PaymentProcessor;
use App\Models\User;
use App\Repositories\UserFaucetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash as LaracastsFlash;
use Laracasts\Flash\Flash;
use Mews\Purifier\Purifier;
use Prettus\Repository\Criteria\RequestCriteria;

class UserFaucetsController extends Controller
{
    /** @var  UserFaucetRepository */
    private $userFaucetRepository;

    public function __construct(UserFaucetRepository $userFaucetRepo)
    {
        $this->userFaucetRepository = $userFaucetRepo;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the Faucet.
     *
     * @param $userSlug
     * @param Request $request
     * @return Response
     */
    public function index($userSlug, Request $request)
    {
        $this->userFaucetRepository->pushCriteria(new RequestCriteria($request));
        $user = User::where('slug', $userSlug)->first();
        if(empty($user)){
            abort(404);
        }
        $faucets = $user->faucets()->get();

        $paymentProcessors = PaymentProcessor::orderBy('name', 'asc')->pluck('name', 'id');

        return view('users.faucets.index')
            ->with('user', $user)
            ->with('faucets', $faucets)
            ->with('paymentProcessors', $paymentProcessors);

    }

    /**
     * Show the form for creating a new Faucet.
     *
     * @param $userSlug
     * @return Response
     */
    public function create($userSlug)
    {
        $paymentProcessors = PaymentProcessor::orderBy('name', 'asc')->get();
        $user = User::where('slug', $userSlug)->first();
        $faucetPaymentProcessorIds = null;

        $faucets = Faucet::distinct()->orderBy('name', 'asc')->get();
        $userFaucets = $user->faucets()->orderBy('name', 'asc')->get();

        $availableFaucets = $faucets->except($userFaucets->modelKeys());

        $faucet = null;
        return view('users.faucets.create')
            ->with('paymentProcessors', $paymentProcessors)
            ->with('faucetPaymentProcessorIds', $faucetPaymentProcessorIds)
            ->with('faucets', $availableFaucets)
            ->with('user', $user);

    }

    /**
     * Store a newly created Faucet in storage.
     *
     * @param $userSlug
     * @param CreateUserFaucetRequest $request
     * @return Response
     */
    public function store($userSlug, CreateUserFaucetRequest $request)
    {
        if(Auth::user()->hasRole('owner') || Auth::user()){
            $input = $request->except('_token');
            $userId = (int)$request->get('user_id');
            $user = User::where('id', $userId)->first();
            $userFaucet = $this->userFaucetRepository->create($input);

            LaracastsFlash::success('Faucet saved successfully.');

            return redirect(route('users.faucets', ['userSlug' => $user->slug]));
        }

    }

    /**
     * Display the specified Faucet.
     *
     * @param $userSlug
     * @param $faucetSlug
     * @return Response
     */
    public function show($userSlug, $faucetSlug)
    {
        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->first();

        $message = null;

        if(Auth::guest() && !empty($faucet) && $faucet->isDeleted()){ // If the visitor is a guest, faucet exists, and faucet is soft-deleted
            LaracastsFlash::error('Faucet not found');
            return redirect(route('faucets.index'));
        }
        else if(
            !Auth::guest() && // If the visitor isn't a guest visitor,
            Auth::user()->hasRole('user') && // If the visitor is an authenticated user with 'user' role
            !Auth::user()->hasRole('owner') && // If the visitor is an authenticated user, but without 'owner' role,
            $faucet->isDeleted() // If the faucet has been soft-deleted
        ){
            LaracastsFlash::error('Faucet not found');
            return redirect(route('faucets.index'));
        }
        else{
            if(
                !empty($faucet) && // If the faucet exists,
                $faucet->isDeleted() && // If the faucet is soft-deleted,
                Auth::user()->hasRole('owner') // If the currently authenticated user has 'owner' role,
            ){
                if(Auth::user()->hasRole('owner')){
                    $message = 'The faucet has been temporarily deleted. You can restore the faucet or permanently delete it.';
                }

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('message', $message);
            }
            if(!empty($faucet) && !$faucet->isDeleted()){ // If the faucet exists and isn't soft-deleted

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('message', $message);
            } else{
                LaracastsFlash::error('Faucet not found');
                return redirect(route('users.faucets', $user->slug));
            }
        }
    }

    /**
     * Show the form for editing the specified Faucet.
     *
     * @param $userSlug
     * @param $faucetSlug
     * @return Response
     *
     */
    public function edit($userSlug, $faucetSlug)
    {
        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->first();

        if (empty($faucet)) {
            Flash::error('Faucet not found');

            return redirect(route('users.faucets', $user->slug));
        }

        return view('users.faucets.edit', $user->slug, $faucet->slug)
            ->with('faucet', $faucet);
    }

    /**
     * Update the specified Faucet in storage.
     *
     * @param $userSlug
     * @param $faucetSlug
     * @param UpdateUserFaucetRequest $request
     * @return Response
     */
    public function update($userSlug, $faucetSlug, UpdateUserFaucetRequest $request)
    {
        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->first();
        $input = $request->except('_token', '_method');

        if(empty($user)){abort(404);
        }

        if (empty($faucet)) {
            Flash::error('Faucet not found');

            return redirect(route('users.faucets', $user->slug));
        }

        $this->userFaucetRepository->update($input, $user->id);

        LaracastsFlash::success('Faucet updated successfully.');

        return redirect(route('users.faucets', $user->slug));

    }

    /**
     * Remove the specified Faucet from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($userSlug, $faucetSlug)
    {

    }

    /**
     * @param $userSlug
     * @param $faucetSlug
     */
    public function destroyPermanently($userSlug, $faucetSlug)
    {

    }

    /**
     * @param $userSlug
     * @param $faucetSlug
     */
    public function restoreDeleted($userSlug, $faucetSlug){

    }
}
