<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 3/04/2017
 * Time: 20:33
 */

namespace Helpers\Functions;

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

class Users
{
    private $userRepository;
    private $faucetFunctions;
    public function __construct(UserRepository $userRepository, Faucets $faucetFunctions)
    {
        $this->userRepository = $userRepository;
        $this->faucetFunctions = $faucetFunctions;
    }

    /**
     * Create a user with form data.
     * @param array $data
     * @return User
     */
    public function createStoreUser(array $data){

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

        foreach($initialPermissions as $permission){
            $newUser->attachPermission($permission);
        }

        return $user;
    }

    /**
     * Delete a user.
     * @param $slug
     * @param bool $permanentlyDelete True if user permanently deleted, false for soft-delete.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyUser($slug, bool $permanentlyDelete = false){

        $user = $this->userRepository->findByField('slug', $slug)->first();

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->hasRole('owner') && $user->isDeleted() == true)) {
            LaracastsFlash::error('User not found');

            return redirect(route('users.index'));
        }

        if($user->hasRole('owner') == true){
            LaracastsFlash::error('An owner-user cannot be deleted.');

            return redirect(route('users.index'));
        }

        if($permanentlyDelete == false){
            $this->userRepository->deleteWhere(['slug' => $slug]);
        } else{
            $this->userRepository->deleteWhere(['slug' => $slug], true);
        }
    }

    /**
     * Resore a soft-deleted user.
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreUser($slug){

        $user = $this->userRepository->findByField('slug', $slug)->first();

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->hasRole('owner') && $user->isDeleted() == true)) {
            LaracastsFlash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->restoreDeleted($slug);

    }

    /**
     * Update a specified user.
     * @param $slug
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateUser($slug, UpdateUserRequest $request){

        $user = $this->userRepository->findByField('slug', $slug)->first();
        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && $user->isDeleted() == true)) {
            LaracastsFlash::error('User not found');

            return redirect(route('users.index'));
        }
        else{
            if(($user == Auth::user() || Auth::user()->hasRole('owner')) || ($user->isDeleted() == true && Auth::user()->hasRole('owner')))
            {
                $updateRequestData = $request->has('password') &&
                $request->has('password_confirmation') ?
                    $request->all() :
                    $request->except(['password','password_confirmation']);

                $user = $this->userRepository->update(
                    $updateRequestData,
                    $user->slug
                );

                LaracastsFlash::success('User updated successfully.');

                return redirect(route('users.index'));
            }
        }
    }

    /**
     * Checks if a user is authorised to access a route.
     * @param User $user
     * @param $routeName
     * @param array $routeParameters
     * @param array|null $dataParameters
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function userCanAccessArea(User $user, $routeName, array $routeParameters, array $dataParameters = []){
        if($user->is_admin == false || !$user->hasRole('owner')){
            abort(403);
        }
        $currentRoute = route($routeName, $routeParameters);
        if(!$currentRoute){
            abort(404);
        }
        return redirect($currentRoute)->with($dataParameters);
    }

    public function getPaymentProcessorFaucets(User $user, PaymentProcessor $paymentProcessor, $trashed = false){

        if(empty($user) || empty($paymentProcessor)){
            abort(404);
        }

        $faucets = $this->faucetFunctions->getUserFaucets($user, $trashed);

        $paymentProcessorFaucets = null;

        if($trashed == true){
            $paymentProcessorFaucets = $paymentProcessor->faucets()->withTrashed()->pluck('id');
        } else{
            $paymentProcessorFaucets = $paymentProcessor->faucets()->pluck('id');
        }

        $userFaucets = $faucets->whereIn('id', $paymentProcessorFaucets)->all();

        return $userFaucets;

    }

}