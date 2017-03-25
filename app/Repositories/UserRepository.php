<?php

namespace App\Repositories;

use App\Models\User;
use Mews\Purifier\Facades\Purifier;

class UserRepository extends Repository implements IRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_name',
        'first_name',
        'last_name',
        'email',
        'password',
        'bitcoin_address',
        'remember_token',
        'slug'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * NOTE: New users cannot register as an admin.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $userData = self::cleanInput($data);
        $user = User::create($userData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($user, $userData);
        $user->save();
        return $this->parserResult($user);
    }

    /**
    * Update user data,
    * @param array $data
    * @param $id
    * @return mixed
    */
    public function update(array $data, $slug)
    {
        // Have to skip presenter to get a model not some data
        $user = User::where('slug', $slug)->first();
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $userData = self::cleanInput($data);
        $user = $user->fill($userData);
        $this->skipPresenter($temporarySkipPresenter);
        $user = $this->updateRelations($user, $userData);
        $user->save();
        return $this->parserResult($user);
    }

    static function cleanInput(array $data)
    {
        $data['user_name'] = Purifier::clean($data['user_name'], 'generalFields');
        $data['first_name'] = Purifier::clean($data['first_name'], 'generalFields');
        $data['last_name'] = Purifier::clean($data['last_name'], 'generalFields');
        $data['email'] = Purifier::clean($data['email'], 'generalFields');
        if(isset($data['password']) && isset($data['password_confirmation'])){
            $data['password'] = Purifier::clean(bcrypt($data['password']), 'generalFields');
        }
        $data['bitcoin_address'] = Purifier::clean($data['bitcoin_address'], 'generalFields');
        $data['is_admin'] = Purifier::clean($data['is_admin'], 'generalFields');

        return $data;
    }
}
