<?php

namespace App\Repositories;

use App\Models\Role;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

/**
 * Class RoleRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class RoleRepository extends BaseRepository implements IRepository
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
            'name' => Purifier::clean($data['name'], 'generalFields'),
            'display_name' => Purifier::clean($data['display_name'], 'generalFields'),
            'description' => Purifier::clean($data['description'], 'generalFields')
        ];
    }
}
