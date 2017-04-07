<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Faucets;
use App\Http\Requests\CreateUserFaucetRequest;
use App\Http\Requests\UpdateUserFaucetRequest;
use App\Models\Faucet;
use App\Models\PaymentProcessor;
use App\Models\User;
use App\Repositories\UserFaucetRepository;
use Carbon\Carbon;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Laracasts\Flash\Flash as LaracastsFlash;
use Prettus\Repository\Criteria\RequestCriteria;

class UserFaucetsController extends Controller
{
    /** @var  UserFaucetRepository */
    private $userFaucetRepository;

    private $faucetFunctions;

    public function __construct(UserFaucetRepository $userFaucetRepo, Faucets $faucetFunctions)
    {
        $this->userFaucetRepository = $userFaucetRepo;
        $this->faucetFunctions = $faucetFunctions;
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

        if(Auth::guest() == true){
            $showFaucets = $this->faucetFunctions->getUserFaucets($user, false);
        }

        else{
            $showFaucets = $this->faucetFunctions->getUserFaucets($user, true);
        }

        $paymentProcessors = PaymentProcessor::orderBy('name', 'asc')->pluck('name', 'id');

        return view('users.faucets.index')
            ->with('user', $user)
            ->with('faucets', $showFaucets)
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
        $userFaucets = null;
        if(Auth::user()->hasRole('user') || Auth::user()->hasRole('owner')){
            $userFaucets = $this->faucetFunctions->getUserFaucets($user, true);
        } else{
            $userFaucets = $this->faucetFunctions->getUserFaucets($user, false);
        }

        $availableFaucets = $faucets->except($userFaucets->modelKeys());

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
            $this->userFaucetRepository->create($input);

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

        $mainFaucet = Faucet::where('slug', $faucetSlug)->withTrashed()->first();

        $message = null;

        if(empty($user)){
            abort(404);
        }
        else if(empty($faucet) && $mainFaucet != null) {
            if (Auth::guest()) {
                LaracastsFlash::error('The faucet was not found');
                return redirect(route('users.faucets', $user->slug));
            } else if (Auth::user()->hasRole('owner') || Auth::user()->hasRole('user')) {
                $message = null;
                if (Auth::user()->hasRole('owner')) {
                    $message = 'The faucet you requested has been temporarily deleted. You can restore the faucet or permanently delete it.';
                }
                else if (Auth::user()->hasRole('user')) {
                    $message = 'You have deleted the faucet that was requested; however, you are able to restore this faucet.';
                }

                return redirect(route('users.faucets', $user->slug))->with('message', $message);
            }
        }
        else if(!empty($faucet) && $faucet->isDeleted()){
            if(!Auth::guest() && (Auth::user()->hasRole('owner') || Auth::user()->hasRole('user')))
            {
                if(Auth::user()->hasRole('user')){
                    $message = 'The owner of this rotator has deleted the faucet you requested. You can contact them if you would like it to be restored.';
                }
                elseif (Auth::user()->hasRole('owner')){
                    $message = 'The faucet has been temporarily deleted. You can restore the faucet or permanently delete it.';
                }

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('message', $message);
            }
        }

        else if(!empty($faucet) && ($faucet->pivot->deleted_at != null || $faucet->isDeleted())){
            if(!Auth::guest() && (Auth::user()->hasRole('owner') || Auth::user()->hasRole('user')))
            {
                if(Auth::user()->hasRole('user')){
                    $message = 'You have deleted the faucet that was requested; however, you are able to restore this faucet.';
                }
                elseif (Auth::user()->hasRole('owner')){
                    $message = 'The faucet has been temporarily deleted. You can restore the faucet or permanently delete it.';
                }

                return view('users.faucets.show')
                    ->with('user', $user)
                    ->with('faucet', $faucet)
                    ->with('message', $message);
            }
        }
        else{
            LaracastsFlash::error('The faucet was not found');
            return redirect(route('users.faucets', $user->slug));
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
            LaracastsFlash::error('Faucet not found');

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

        if(empty($user)){
            abort(404);
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
     * Soft-delete the specified Faucet from storage.
     *
     * @param $userSlug
     * @param $faucetSlug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($userSlug, $faucetSlug)
    {
        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->withTrashed()->first();

        if(empty($user)){
            abort(404);
        }

        if (empty($faucet)) {
            LaracastsFlash::error('Faucet not found');

            return redirect(route('users.faucets', $user->slug));
        }

        if(!empty($faucet) && $faucet->isDeleted()){
            LaracastsFlash::error('The owner has temporarily deleted this faucet, and you are not able to restore it. You can ask the owner if the faucet can be restored.');

            return redirect(route('users.faucets', $user->slug));
        }

        if(!empty($faucet) && $faucet->pivot->deleted_at != null){
            LaracastsFlash::error('The faucet has already been soft-deleted.');

            return redirect(route('users.faucets', $user->slug));
        }

        DB::table('referral_info')
            ->where('user_id', $user->id)
            ->where('faucet_id', $faucet->id)
            ->update(['deleted_at' => Carbon::now()]);

        LaracastsFlash::success('The faucet has successfully been soft-deleted!');

        return redirect(route('users.faucets', $user->slug));
    }

    /**
     * Permanently delete the specified Faucet from storage.
     *
     * @param $userSlug
     * @param $faucetSlug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($userSlug, $faucetSlug)
    {
        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->first();

        if(empty($user)){
            abort(404);
        }

        if (empty($faucet)) {
            LaracastsFlash::error('The faucet was not found, or has already been permanently deleted.');

            return redirect(route('users.faucets', $user->slug));
        }

        DB::table('referral_info')
            ->where('user_id', $user->id)
            ->where('faucet_id', $faucet->id)
            ->delete();

        LaracastsFlash::success('The faucet was permanently deleted!');

        return redirect(route('users.faucets', $user->slug));
    }

    /**
     * @param $userSlug
     * @param $faucetSlug
     */
    public function restoreDeleted($userSlug, $faucetSlug){

        $user = User::where('slug', $userSlug)->first();
        $faucet = $user->faucets()->where('slug', '=', $faucetSlug)->withTrashed()->first();

        if(empty($user)){
            abort(404);
        }

        if (empty($faucet)) {
            LaracastsFlash::error('The faucet was not found, or has been permanently deleted.');

            return redirect(route('users.faucets', $user->slug));
        }

        if(!empty($faucet) && $faucet->isDeleted()){
            LaracastsFlash::error('The owner has temporarily deleted this faucet, and you are not able to restore it. You can ask the owner if the faucet can be restored.');

            return redirect(route('users.faucets', $user->slug));
        }

        if($faucet->pivot->deleted_at == null){
            LaracastsFlash::error('The faucet is already active, and hasn\'t been deleted.');

            return redirect(route('users.faucets', $user->slug));
        }

        DB::table('referral_info')
            ->where('user_id', $user->id)
            ->where('faucet_id', $faucet->id)
            ->update(['deleted_at' => null]);

        LaracastsFlash::success('The faucet was successfully restored!');

        return redirect(route('users.faucets', $user->slug));

    }
}
