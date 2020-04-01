<?php

namespace App\Repositories;

use App\Models\User;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class UserRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
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
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $data['is_admin'] = 0; // Set is_admin to false, cannot add new admins for now.
        $userData = self::cleanInput($data);
        $user = User::create($userData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($user, $userData);
        $user->save();
        return $this->parserResult($user);
    }

    /**
     * Update user data,
     *
     * @param  array $data
     * @param  $slug
     * @return mixed
     */
    public function update(array $data, $slug)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $user = User::where('slug', $slug)->withTrashed()->first();
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $userData = self::cleanInput($data);
        $user = $user->fill($userData);
        $this->skipPresenter($temporarySkipPresenter);
        $user = $this->updateRelations($user, $userData);
        $user->save();
        return $this->parserResult($user);
    }

    /**
     * Sanitize user data.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        $data['user_name'] = Purify::clean($data['user_name'], self::$generalFieldsConfig);
        $data['first_name'] = Purify::clean($data['first_name'], self::$generalFieldsConfig);
        $data['last_name'] = Purify::clean($data['last_name'], self::$generalFieldsConfig);
        $data['email'] = Purify::clean($data['email'], self::$generalFieldsConfig);
        if (!empty($data['password']) && !empty($data['password_confirmation'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $data['bitcoin_address'] = Purify::clean($data['bitcoin_address'], self::$generalFieldsConfig);
        if (isset($data['is_admin'])) {
            $data['is_admin'] = Purify::clean($data['is_admin'], self::$generalFieldsConfig);
        }

        return $data;
    }
}
