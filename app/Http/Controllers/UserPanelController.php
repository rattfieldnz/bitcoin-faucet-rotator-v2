<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Helpers\Functions\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserPanelController extends Controller
{
    private $userRepository;
    private $userFunctions;

    /**
     * UserPanelController constructor.
     *
     * @param UserRepository $userRepo
     * @param Users $userFunctions
     */
    public function __construct(UserRepository $userRepo, Users $userFunctions)
    {
        $this->userRepository = $userRepo;
        $this->userFunctions = $userFunctions;
        $this->middleware('auth');
    }

    public function show($userSlug)
    {

        $user = null;
        if(Auth::user()->isAnAdmin()) {
            $user = $this->userRepository->findByField('slug', $userSlug, true)->first();
        } else if(Auth::user()) {
            $user = $this->userRepository->findByField('slug', $userSlug)->first();
        }

        if(empty($user)) {
            dd("No such user");
        }

        if($user != Auth::user() && !Auth::user()->isAnAdmin()) {
            dd("You are not allowed to access another user's panel.");
        }

        return view('users.panel.show')
            ->with('user', $user);
    }
}
