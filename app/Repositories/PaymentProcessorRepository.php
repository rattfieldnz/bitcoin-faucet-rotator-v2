<?php

namespace App\Repositories;

use App\Models\PaymentProcessor;
use Mews\Purifier\Facades\Purifier;

class PaymentProcessorRepository extends Repository implements IRepository
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

    /**
     * Create a new payment processor.
     *
     * @param  array $data
     * @return PaymentProcessor
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $paymentProcessorData = self::cleanInput($data);
        $paymentProcessor = parent::create($paymentProcessorData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($paymentProcessor, $paymentProcessorData);
        $paymentProcessor->save();
        return $this->parserResult($paymentProcessor);
    }

    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $paymentProcessor = PaymentProcessor::where('id', '=', $id)->withTrashed()->first();
        $paymentProcessorData = self::cleanInput($data);
        $paymentProcessor = $paymentProcessor->fill($paymentProcessorData);
        $this->skipPresenter($temporarySkipPresenter);
        $paymentProcessor = $this->updateRelations($paymentProcessor, $paymentProcessorData);
        $paymentProcessor->save();
        return $this->parserResult($paymentProcessor);
    }

    public static function cleanInput(array $data)
    {
        return [
            'name' => Purifier::clean($data['name'], 'generalFields'),
            'url' => Purifier::clean($data['url'], 'generalFields'),
            'meta_title' => Purifier::clean($data['meta_title'], 'generalFields'),
            'meta_description' => Purifier::clean($data['meta_description'], 'generalFields'),
            'meta_keywords' => Purifier::clean($data['meta_keywords'], 'generalFields')
        ];
    }
}
