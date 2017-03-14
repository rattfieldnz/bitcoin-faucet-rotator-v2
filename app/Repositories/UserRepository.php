<?php

namespace App\Repositories;

use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InfyOm\Generator\Common\BaseRepository;

class UserRepository extends BaseRepository
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
        $userData = [
            'user_name' => $data['user_name'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'bitcoin_address' => $data['bitcoin_address'],
            'is_admin' => $data['is_admin']
        ];
        $user = User::create($userData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($user, $userData);
        $user->save();
        return $this->parserResult($user);
    }
}
