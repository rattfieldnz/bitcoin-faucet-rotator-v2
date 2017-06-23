<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;

class UserController extends AppBaseController
{
    private $userRepository;
    private $userFunctions;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepo
     * @param Users $userFunctions
     */
    public function __construct(UserRepository $userRepo, Users $userFunctions)
    {
        $this->userRepository = $userRepo;
        $this->userFunctions = $userFunctions;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = null;
        if (Auth::guest() || Auth::user()->hasRole('user')) {
            $users = $this->userRepository->all();
        } elseif (Auth::user()->isAnAdmin()) {
            $users = $this->userRepository->withTrashed()->get();
        }

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = null;
        if (Auth::user()->isAnAdmin()) {
            return view('users.create')->with('user');
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateUserRequest $request)
    {
        if (Auth::user()->isAnAdmin()) {
            $input = $request->all();

            $this->userFunctions->createStoreUser($input);

            flash('User saved successfully.')->success();

            return redirect(route('users.index'));
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified User.
     *
     * @param $slug
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($slug)
    {
        $user = null;
        // If the slug value is 'admin', return admin user with their real slug.
        // We don't want to show the real admin user's user name.
        $adminUser = $this->userRepository->findByField('is_admin', true)->first();
        if ($slug == 'admin') {
            if ($adminUser->isAnAdmin()) {
                $user = $adminUser;
            }
        } else {
            $user = $this->userRepository->findByField('slug', $slug)->first();
        }
        if ($adminUser->slug == $slug) {
            if (Auth::user() != null && Auth::user()->isAnAdmin()) {
                return view('users.show')
                    ->with('user', $user);
            }
            flash('User not found')->error();
            return redirect(route('users.index'));
        }
        if (Auth::guest()) {
            if (!empty($user)) {
                return view('users.show')
                    ->with('user', $user);
            } else {
                flash('User not found')->error();
                return redirect(route('users.index'));
            }
        }

        $message = null;

        if (Auth::guest() && !empty($user) && $user->isDeleted()) { // If the visitor is a guest, user doesn't exist, and user is soft-deleted
            flash('User not found')->error();
            return redirect(route('users.index'));
        } elseif (
            !Auth::guest() && // If the visitor isn't a guest visitor,
            Auth::user()->hasRole('user') && // If the visitor is an authenticated user with 'user' role
            $user->isDeleted() // If the requested user has been soft-deleted
        ) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        } elseif ((Auth::guest() || Auth::user()->hasRole('user')) && $user->isAnAdmin()) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        } else {
            if (
                !empty($user) && // If the user exists,
                $user->isDeleted() && // If the user is soft-deleted,
                Auth::user()->isAnAdmin() // If the currently authenticated user is an admin,
            ) {
                $message = 'The user has been temporarily deleted. You can restore the user or permanently delete them.';

                return view('users.show')
                    ->with('user', $user)
                    ->with('message', $message);
            }
            if (!empty($user) && !$user->isDeleted()) { // If the user exists and isn't soft-deleted

                return view('users.show')
                    ->with('user', $user);
            } else {
                flash('User not found')->error();
                return redirect(route('users.index'));
            }
        }
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param $slug
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->isAnAdmin() && $user->isDeleted() == true)) {
            flash('User not found')->error();

            return redirect(route('users.index'));
        } else {
            if (($user == Auth::user() || Auth::user()->isAnAdmin()) || ($user->isDeleted() == true && Auth::user()->isAnAdmin())) {
                return view('users.edit')
                    ->with('user', $user)
                    ->with('slug', $slug);
            }
            abort(403);
        }
    }

    /**
     * Update the specified User in storage.
     *
     * @param $slug
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($slug, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();

        flash('User updated successfully.')->success();
        //dd($request->session()->get('flash_notification'));
        return $this->userFunctions->updateUser($user->slug, $request);
    }

    /**
     * Remove the specified User from storage.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.destroy',
            ['user' => $user, 'slug' => $slug],
            ['user' => $user, 'slug' => $slug]
        );

        if ($user->isAnAdmin()) {
            flash('An owner-admin-user cannot be soft-deleted.')->error();

            return redirect(route('users.index'));
        }
        $this->userFunctions->destroyUser($user->slug, false);

        flash('User deleted successfully.')->success();

        return redirect(route('users.index'));
    }

    /**
     * Permanently delete the specified user.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.delete-permanently',
            ['user' => $user, 'slug' => $slug],
            ['user' => $user, 'slug' => $slug]
        );

        if ($user->isAnAdmin()) {
            flash('An owner-admin-user cannot be permanently deleted.')->error();

            return redirect(route('users.index'));
        }
        if (empty($user)) {
            flash('User not found.')->error();

            return redirect(route('users.index'));
        }
        $user->forceDelete();
        DB::table('referral_info')
            ->where('user_id', $user->id)
            ->delete();

        flash('User was permanently deleted!')->success();

        return redirect(route('users.index'));
    }

    /**
     * Restore the specified soft-deleted user.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.restore',
            ['user' => $user, 'slug' => $slug],
            ['user' => $user, 'slug' => $slug]
        );

        $this->userFunctions->restoreUser($user->slug);

        flash('User was successfully restored!')->success();

        return redirect(route('users.index'));
    }
}
