<?php

namespace App\Repositories;

use App\Models\Permission;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;

class PermissionRepository extends BaseRepository implements IRepository
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
        return Permission::class;
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
