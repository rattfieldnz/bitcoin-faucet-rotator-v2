<?php

namespace App\Http\Controllers\Auth;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /** @var  UserRepository */
    private $userRepository;

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/faucets';

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo){
        $this->userRepository = $userRepo;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, User::$rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * NOTE: New users cannot register as an admin.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
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

        foreach($initialPermissions as $permission){
            $newUser->attachPermission($permission);
        }

        return $user;
        /*return User::create([
            'user_name' => $data['user_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'bitcoin_address' => $data['bitcoin_address'],
            'is_admin' => false
        ]);*/
    }
}
