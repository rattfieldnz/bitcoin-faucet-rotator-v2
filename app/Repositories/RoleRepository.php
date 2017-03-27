<?php

namespace App\Repositories;

use App\Models\Role;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

class RoleRepository extends BaseRepository implements IRepository
{
    /**
     * @var array
     */
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

    static function cleanInput(array $data)
    {
        return [
            'name' => Purifier::clean($data['name'], 'generalFields'),
            'display_name' => Purifier::clean($data['display_name'], 'generalFields'),
            'description' => Purifier::clean($data['description'], 'generalFields')
        ];
    }
}
