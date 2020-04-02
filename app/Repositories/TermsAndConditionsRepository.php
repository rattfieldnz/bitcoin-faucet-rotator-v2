<?php

namespace App\Repositories;

use App\Models\TermsAndConditions;

/**
 * Class TermsAndConditionsRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class TermsAndConditionsRepository extends Repository
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
