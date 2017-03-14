<?php

namespace App\Repositories;

use App\Models\AdBlock;
use InfyOm\Generator\Common\BaseRepository;

class AdBlockRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ad_content',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AdBlock::class;
    }
}
