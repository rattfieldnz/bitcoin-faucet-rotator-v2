<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->userRepository->withTrashed()->get();

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {

        $user = null;
        Functions::userCanAccessArea(
            Auth::user(),
            'users.create',
            null,
            [
                'user' => $user
            ]
        );
        return view('users.create')->with('user');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        Functions::userCanAccessArea(
            Auth::user(),
            'users.store',
            null,
            null
        );
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug, true)->first();

        if (empty($user) || (Auth::guest() && $user->isDeleted() == true)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        if(!Auth::user()->is_admin && $user->isDeleted() == true){
            abort(403);
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.edit',
            null,
            [
                'user' => $user,
                'slug' => $slug
            ]
        );

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')
            ->with('user', $user)
            ->with('slug', $slug);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($slug, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.update',
            null,
            [
                'user' => $user,
                'slug' => $slug
            ]
        );

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $updateRequestData = $request->has('password') &&
            $request->has('password_confirmation') ?
            $request->all() :
            $request->except(['password','password_confirmation']);

        $user = $this->userRepository->update(
            $updateRequestData,
            $user->slug
        );

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.destroy',
            null,
            [
                'user' => $user,
                'slug' => $slug
            ]
        );

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->deleteWhere(['slug' => $slug]);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    public function destroyPermanently($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.delete-permanently',
            null,
            [
                'user' => $user,
                'slug' => $slug
            ]
        );

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->deleteWhere(['slug' => $slug], true);

        Flash::success('User was permanently deleted!');

        return redirect(route('users.index'));

    }

    public function restoreDeleted($slug){
        $user = $this->userRepository->findByField('slug', $slug)->first();
        Functions::userCanAccessArea(
            Auth::user(),
            'users.restore',
            null,
            [
                'user' => $user,
                'slug' => $slug
            ]
        );

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->restoreDeleted($slug);

        Flash::success('User was successfully restored!');

        return redirect(route('users.index'));

    }
}
