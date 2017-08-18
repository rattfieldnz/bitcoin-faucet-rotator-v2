<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Helpers\Functions\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class UserPanelController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class UserPanelController extends Controller
{
    private $userRepository;
    private $userFunctions;

    /**
     * UserPanelController constructor.
     *
     * @param UserRepository $userRepo
     * @param Users          $userFunctions
     */
    public function __construct(UserRepository $userRepo, Users $userFunctions)
    {
        $this->userRepository = $userRepo;
        $this->userFunctions = $userFunctions;
        $this->middleware('auth');
    }

    /**
     * Show a user's panel.
     * TODO: Fill page with useful data - possibly using tabbed interface, charts, etc.
     *
     * @param  $userSlug
     * @return UserPanelController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($userSlug)
    {
        $user = null;

        // Let admin show user panel if that user was suspended or not.
        // Otherwise if not an admin, retrieve user details from requested slug.
        if (Auth::user()->isAnAdmin()) {
            $user = $this->userRepository->findByField('slug', $userSlug, true)->first();
        } elseif (Auth::user()) {
            $user = $this->userRepository->findByField('slug', $userSlug)->first();
        }

        // Below conditional covers if the user account was suspended or completely removed,
        // while they were already logged in.
        if (empty($user)) {
            if (Auth::user()->isAnAdmin()) {
                flash(
                    "Sorry, there is no matching user, 
                    or the user has recently been suspended."
                )->error();

                return redirect(route('users.index'));
            }

            flash(
                "Sorry, your account was recently suspended or permanently deleted. 
                Please contact admin for further information."
            )->error();

            return redirect(route('login'));
        }

        // This conditional prevents users from viewing other user's panels,
        // where only admin can see any user panel.
        if ($user != Auth::user() && !Auth::user()->isAnAdmin()) {
            flash("Sorry, you are not allowed to access another user's panel.")
                ->error();

            return redirect(route('users.index'));
        }

        return view('users.show')
            ->with('user', $user);
    }
}
