<?php

namespace App\Repositories;

use App\Models\TermsAndConditions;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class TermsAndConditionsRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class TermsAndConditionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'short_description',
        'content',
        'keywords'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TermsAndConditions::class;
    }
}
