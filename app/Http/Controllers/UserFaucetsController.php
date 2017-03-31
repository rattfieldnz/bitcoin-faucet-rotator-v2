<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateUserFaucetRequest;
use App\Http\Requests\UpdateUserFaucetRequest;
use App\Models\PaymentProcessor;
use App\Repositories\UserFaucetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash as LaracastsFlash;
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

    }

    /**
     * Show the form for creating a new Faucet.
     *
     * @param $userSlug
     * @return Response
     */
    public function create($userSlug)
    {

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

    }

    /**
     * Update the specified Faucet in storage.
     *
     * @param  int              $id
     * @param UpdateUserFaucetRequest $request
     *
     * @return Response
     */
    public function update($userSlug, $faucetSlug, UpdateUserFaucetRequest $request)
    {

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
