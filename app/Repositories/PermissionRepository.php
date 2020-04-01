<?php

namespace App\Repositories;

use App\Models\Permission;
use Mews\Purifier\Facades\Purifier;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class PermissionRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class PermissionRepository extends Repository implements IRepository
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
        return Permission::class;
    }

    /**
     * Sanitize permission data.
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
