<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\Functions\Users;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

/**
 * Class RegisterController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\Auth
 */
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

    private $userFunctions;

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
     * @param Users $userFunctions
     */
    public function __construct(Users $userFunctions)
    {
        $this->userFunctions = $userFunctions;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = User::$rules;
        $rules['g-recaptcha-response'] = 'required|recaptcha';
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * NOTE: New users cannot register as an admin.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return $this->userFunctions->createStoreUser($data);
    }
}
