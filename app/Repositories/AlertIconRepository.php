<?php

namespace App\Repositories;

use App\Models\AlertIcon;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AlertIconRepository
 * @package App\Repositories
 * @version November 6, 2017, 6:54 pm NZDT
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 *
 * @method AlertIcon findWithoutFail($id, $columns = ['*'])
 * @method AlertIcon find($id, $columns = ['*'])
 * @method AlertIcon first($columns = ['*'])
*/
class AlertIconRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'icon_class'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AlertIcon::class;
    }
}
