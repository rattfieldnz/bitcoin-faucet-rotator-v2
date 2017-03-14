<?php

namespace App\Repositories;

use App\Models\PaymentProcessor;
use InfyOm\Generator\Common\BaseRepository;

class PaymentProcessorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'url',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentProcessor::class;
    }
}
