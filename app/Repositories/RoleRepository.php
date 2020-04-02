<?php

namespace App\Repositories;

use App\Models\Role;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class RoleRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class RoleRepository extends Repository implements IRepository
{
    protected $fieldSearchable = [
        'name',
        'display_name',
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
    }

    /**
     * Update the specified role.
     *
     * @param array $data
     * @param $id
     *
     * @return mixed
     * @throws ValidatorException
     */
    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $faucetData = self::cleanInput($data);
        $role = parent::update($faucetData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $role = $this->updateRelations($role, $faucetData);
        $role->save();
        return $this->parserResult($role);
    }

    /**
     * Sanitize role data.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        return [
            'name' => Purify::clean($data['name'], self::$generalFieldsConfig),
            'display_name' => Purify::clean($data['display_name'], self::$generalFieldsConfig),
            'description' => Purify::clean($data['description'], self::$generalFieldsConfig)
        ];
    }
}
