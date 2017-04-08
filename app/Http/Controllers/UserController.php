<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash as LaracastsFlash;
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
        if(Auth::guest() || Auth::user()->hasRole('user')){
            $users = $this->userRepository->findByField('is_admin', false);
        }
        else if(Auth::user()->hasRole('owner')){
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
        if(Auth::user()->hasRole('owner'))
        {
            return view('users.create')->with('user');
        } else{
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
        if(Auth::user()->hasRole('owner')) {
            $input = $request->all();

            $this->userFunctions->createStoreUser($input);

            LaracastsFlash::success('User saved successfully.');

            return redirect(route('users.index'));
        } else{
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
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $message = null;

        if(Auth::guest() && !empty($user) && $user->isDeleted()){ // If the visitor is a guest, user exists, and user is soft-deleted
            LaracastsFlash::error('User not found');
            return redirect(route('users.index'));
        }
        else if(
            !Auth::guest() && // If the visitor isn't a guest visitor,
            Auth::user()->role()->first()->name == 'user' && // If the visitor is an authenticated user with 'user' role
            $user->isDeleted() // If the requested user has been soft-deleted
        ){
            LaracastsFlash::error('User not found');
            return redirect(route('users.index'));
        }
        else{
            if(
                !empty($user) && // If the user exists,
                $user->isDeleted() && // If the user is soft-deleted,
                Auth::user()->role()->first()->name == 'owner' // If the currently authenticated user has 'owner' role,
            ){
                $message = 'The user has been temporarily deleted. You can restore the user or permanently delete them.';

                return view('users.show')
                    ->with('user', $user)
                    ->with('message', $message);
            }
            if(!empty($user) && !$user->isDeleted()){ // If the user exists and isn't soft-deleted

                return view('users.show')
                    ->with('user', $user);
            } else{
                LaracastsFlash::error('User not found');
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
        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->hasRole('owner') && $user->isDeleted() == true)) {
            LaracastsFlash::error('User not found');

            return redirect(route('users.index'));
        }
        else{
            if(($user == Auth::user() || Auth::user()->hasRole('owner')) || ($user->isDeleted() == true && Auth::user()->hasRole('owner')))
            {

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

        if($user->hasRole('owner')){
            LaracastsFlash::error('An owner-user cannot be soft-deleted.');

            return redirect(route('users.index'));
        }
        $this->userFunctions->destroyUser($user->slug, false);

        LaracastsFlash::success('User deleted successfully.');

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


        if($user->hasRole('owner')){
            LaracastsFlash::error('An owner-user cannot be permanently deleted.');

            return redirect(route('users.index'));
        }
        $this->userFunctions->destroyUser($user->slug, true);

        LaracastsFlash::success('User was permanently deleted!');

        return redirect(route('users.index'));

    }

    /**
     * Restore the specified soft-deleted user.
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug){
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.restore',
            ['user' => $user, 'slug' => $slug],
            ['user' => $user, 'slug' => $slug]
        );

        $this->userFunctions->restoreUser($user->slug);

        LaracastsFlash::success('User was successfully restored!');

        return redirect(route('users.index'));

    }
}
