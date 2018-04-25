<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Libraries\Seo\SeoConfig;
use App\Models\MainMeta;
use App\Models\Permission;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use App\Helpers\Functions\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class UserController extends AppBaseController
{
    private $userRepository;
    private $userFunctions;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepo
     * @param Users          $userFunctions
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
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = null;
        if (Auth::check() && Auth::user()->isAnAdmin()) {
            $users = $this->userRepository->withTrashed()->get();
        } else {
            $users = $this->userRepository->all();
        }


        $seoConfig = new SeoConfig();
        $seoConfig->title = "List of Current Users (" . count($users) . ")";
        $seoConfig->description = "View all users currently active/registered on the Bitcoin faucet rotator. There are " .
            count($users) . " users registered, including the admin user.";
        $seoConfig->keywords = ['Users', 'Bitcoin Faucet Rotator users', 'List of Users'];
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('users.index');
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "List of Users";
        WebsiteMeta::setCustomMeta($seoConfig);

        $disqusIdentifier = 'list-of-registered-users';

        return view('users.index')
            ->with('users', $users)
            ->with('currentUrl', $seoConfig->currentUrl)
            ->with('disqusIdentifier', $disqusIdentifier);
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
            return abort(403);
        }
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  CreateUserRequest $request
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
            return abort(403);
        }
    }

    /**
     * Display the specified User.
     *
     * @param  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $message = null;

        if (empty($user) && (Auth::guest() || Auth::user() != null && !Auth::user()->isAnAdmin())) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        }

        if (Auth::guest() && !empty($user) && $user->isDeleted()) { // If the visitor is a guest, user doesn't exist, and user is soft-deleted
            flash('User not found')->error();
            return redirect(route('users.index'));
        } elseif (!Auth::guest()  // If the visitor isn't a guest visitor,
            && Auth::user()->hasRole('user')  // If the visitor is an authenticated user with 'user' role
            && $user->isDeleted() // If the requested user has been soft-deleted
        ) {
            flash('User not found')->error();
            return redirect(route('users.index'));
        } else {
            if (!empty($user)  // If the user exists,
                && $user->isDeleted()  // If the user is soft-deleted,
                && Auth::user()->isAnAdmin() // If the currently authenticated user is an admin,
            ) {
                $message = 'The user has been temporarily deleted. You can restore the user or permanently delete them.';

                $userFaucets = $user->faucets()->withTrashed()->get();

                Users::setMeta($user);

                $disqusIdentifier = 'users-' . $user->slug;

                return view('users.show')
                    ->with('user', $user)
                    ->with('faucets', $userFaucets)
                    ->with('currentUrl', route('users.show', ['slug' => $user->slug]))
                    ->with('disqusIdentifier', $disqusIdentifier)
                    ->with('message', $message);
            }
            if (!empty($user) && !$user->isDeleted()) { // If the user exists and isn't soft-deleted
                $userFaucets = $user->faucets()->get();

                Users::setMeta($user);

                $disqusIdentifier = 'users-' . $user->slug . $user->id;

                return view('users.show')
                    ->with('user', $user)
                    ->with('faucets', $userFaucets)
                    ->with('currentUrl', route('users.show', ['slug' => $user->slug]))
                    ->with('disqusIdentifier', $disqusIdentifier);
            } else {
                flash('User not found')->error();
                return redirect(route('users.index'));
            }
        }
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  $slug
     * @return \Illuminate\View\View
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
            return abort(403);
        }
    }

    /**
     * Update the specified User in storage.
     *
     * @param  $slug
     * @param  UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($slug, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();

        if (empty($user)) {
            flash('User not found')->error();

            return redirect(route('users.index'));
        }

        if ($user->id == Auth::user()->id || Auth::user()->isAnAdmin()) {
            $this->userFunctions->updateUser($user->slug, $request);

            if ($user->id == Auth::user()->id) {
                flash('You have successfully updated your profile!')->success();
            } elseif (Auth::user()->isAnAdmin()) {
                flash('The user profile for \''. $user->user_name . '\' was successfully updated!')->success();
            }

            return redirect(route('users.index'));
        }
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $userName = $user->user_name;
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

        flash('The user \'' . $userName . '\' was successfully archived/deleted!')->success();

        return redirect(route('users.index'));
    }

    /**
     * Permanently delete the specified user.
     *
     * @param  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $userName = $user->user_name;
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

        flash('The user \'' . $userName . '\' was permanently deleted!')->success();

        return redirect(route('users.index'));
    }

    /**
     * Restore the specified soft-deleted user.
     *
     * @param  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $userName = $user->user_name;
        Functions::userCanAccessArea(
            Auth::user(),
            'users.restore',
            ['user' => $user, 'slug' => $slug],
            ['user' => $user, 'slug' => $slug]
        );

        $this->userFunctions->restoreUser($user->slug);

        flash('The user \'' . $userName . '\' was successfully restored!')->success();

        return redirect(route('users.index'));
    }

    /**
     * Purge all archived / soft-deleted users.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function purgeArchivedUsers()
    {
        Functions::userCanAccessArea(Auth::user(), 'users.purge-archived', [], []);

        $purged = $this->userFunctions->purgeArchivedUsers();

        $purgedCount = $purged['count'];

        flash(($purgedCount == 0 ? 'No' : $purgedCount) . ' archived users were permanently deleted!')->success();

        return redirect(route('users.index'));
    }
}
