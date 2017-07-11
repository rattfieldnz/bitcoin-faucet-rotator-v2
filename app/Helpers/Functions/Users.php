<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 3/04/2017
 * Time: 20:33
 */

namespace Helpers\Functions;

use App\Helpers\Constants;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Faucet;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\PaymentProcessor;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash as LaracastsFlash;
use App\Helpers\Functions\Faucets;

/**
 * Class Users
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package Helpers\Functions
 */
class Users
{
    private $userRepository;
    private $faucetFunctions;

    /**
     * Users constructor.
     *
     * @param \App\Repositories\UserRepository $userRepository
     * @param \App\Helpers\Functions\Faucets   $faucetFunctions
     */
    public function __construct(UserRepository $userRepository, Faucets $faucetFunctions)
    {
        $this->userRepository = $userRepository;
        $this->faucetFunctions = $faucetFunctions;
    }

    /**
     * Create a user with form data.
     *
     * @param  array $data
     * @return User
     */
    public function createStoreUser(array $data)
    {
        $user = $this->userRepository->create($data);

        $newUser = User::where('slug', $user->slug)->first();

        $userRole = Role::where('name', 'user')->first();
        $newUser->attachRole($userRole);

        $initialPermissions = [
            Permission::where('name', 'read-users')->first(),
            Permission::where('name', 'read-faucets')->first(),
            Permission::where('name', 'create-user-faucets')->first(),
            Permission::where('name', 'read-user-faucets')->first(),
            Permission::where('name', 'update-user-faucets')->first(),
            Permission::where('name', 'soft-delete-user-faucets')->first(),
            Permission::where('name', 'permanent-delete-user-faucets')->first(),
            Permission::where('name', 'restore-user-faucets')->first(),
            Permission::where('name', 'read-payment-processors')->first(),
        ];

        foreach ($initialPermissions as $permission) {
            $newUser->attachPermission($permission);
        }

        if (Auth::user()->isAnAdmin()) {
            $logMessage = "The user ':subject.user_name' was added by :causer.user_name";
        } else {
            $logMessage = "User ':subject.user_name' has successfully registered.";
        }

        activity(self::userLogName())
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log($logMessage);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param  $slug
     * @param  bool $permanentlyDelete True if user permanently deleted, false for soft-delete.
     * @return bool|int
     */
    public function destroyUser($slug, bool $permanentlyDelete = false)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $logMessage = null;

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->hasRole('owner') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            if ($permanentlyDelete == false) {
                if (Auth::user()->isAnAdmin()) {
                    $logMessage = "The user ':subject.user_name' was suspended by ':causer.user_name'";
                } else {
                    $logMessage = "User ':subject.user_name' has deleted their account";
                }
            } else {
                if (Auth::user()->isAnAdmin()) {
                    $logMessage = "The user ':subject.user_name' was permanently deleted by ':causer.user_name'";
                }
            }

            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log($logMessage);

            return $this->userRepository->deleteWhere(['slug' => $slug], $permanentlyDelete);
        } else {
            return false;
        }
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param  $slug
     * @return bool|mixed
     */
    public function restoreUser($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log("The user ':subject.user_name' was restored by :causer.user_name");

            return $this->userRepository->restoreDeleted($slug);
        } else {
            return false;
        }
    }

    /**
     * Update a specified user.
     *
     * @param  $slug
     * @param  UpdateUserRequest $request
     * @return bool|mixed
     */
    public function updateUser($slug, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $logMessage = null;

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            $updateRequestData = $request->has('password') &&
            $request->has('password_confirmation') ?
                $request->all() :
                $request->except(['password','password_confirmation']);

            if (Auth::user()->isAnAdmin()) {
                if ($user == Auth::user()) {
                    $logMessage = "The ':subject.user_name' has updated their profile";
                } else {
                    $logMessage = "The user ':subject.user_name' was updated by :causer.user_name";
                }
            } else {
                $logMessage = "':subject.user_name' has updated their profile";
            }

            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log($logMessage);

            return $this->userRepository->update(
                $updateRequestData,
                $user->slug
            );
        } else {
            return false;
        }
    }

    /**
     * Checks if a user is authorised to access a route.
     *
     * @param  User       $user
     * @param  $routeName
     * @param  array      $routeParameters
     * @param  array|null $dataParameters
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function userCanAccessArea(User $user, $routeName, array $routeParameters, array $dataParameters = [])
    {
        if (!$user->isAnAdmin()) {
            abort(403);
        }
        $currentRoute = route($routeName, $routeParameters);
        if (!$currentRoute) {
            abort(404);
        }
        return redirect($currentRoute)->with($dataParameters);
    }

    /**
     * Retrieve faucets associated with a payment processor.
     *
     * @param  User             $user
     * @param  PaymentProcessor $paymentProcessor
     * @param  bool             $trashed
     * @return array
     */
    public function getPaymentProcessorFaucets(User $user, PaymentProcessor $paymentProcessor, $trashed = false)
    {
        if (empty($user) || empty($paymentProcessor)) {
            abort(404);
        }

        $faucets = $this->faucetFunctions->getUserFaucets($user, $trashed);

        $paymentProcessorFaucets = null;

        if ($trashed == true) {
            $paymentProcessorFaucets = $paymentProcessor->faucets()->withTrashed()->pluck('id');
        } else {
            $paymentProcessorFaucets = $paymentProcessor->faucets()->pluck('id');
        }

        $userFaucets = $faucets->whereIn('id', $paymentProcessorFaucets)->all();

        return $userFaucets;
    }

    public function userLogName(): string
    {

        if (Auth::user()->isAnAdmin()) {
            return Constants::ADMIN_USER_MANAGEMENT_LOG;
        } else {
            return Constants::USER_MANAGEMENT_LOG;
        }
    }

    public function adminUser()
    {
        $user = $this->userRepository->findByField('slug', Constants::ADMIN_SLUG);
        $user = $user->where('is_admin', 'true')->first();
        return $user->isAnAdmin() ? $user : null;
    }
}
