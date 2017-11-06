<?php

namespace App\Repositories;

use App\Models\AlertType;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AlertTypeRepository
 * @package App\Repositories
 * @version November 6, 2017, 6:49 pm NZDT
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 *
 * @method AlertType findWithoutFail($id, $columns = ['*'])
 * @method AlertType find($id, $columns = ['*'])
 * @method AlertType first($columns = ['*'])
*/
class AlertTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'bootstrap_alert_class'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AlertType::class;
    }
}
