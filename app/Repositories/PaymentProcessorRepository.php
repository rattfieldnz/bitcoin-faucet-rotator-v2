<?php

namespace App\Repositories;

use App\Models\PaymentProcessor;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class PaymentProcessorRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class PaymentProcessorRepository extends Repository implements IRepository
{
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
     * @param array $data
     * @return PaymentProcessor
     * @throws ValidatorException
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

    /**
     * Update a payment processor.
     *
     * @param array $data
     * @param $id
     *
     * @return mixed
     */
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

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @param bool $deleted
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function findItemsWhere(array $where, $columns = ['*'], bool $deleted = false)
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->applyConditions($where);

        $model = $deleted == true ? $this->model->withTrashed()->get($columns) : $this->model->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Santize payment processor data.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        return [
            'name' => Purify::clean($data['name'], self::$generalFieldsConfig),
            'url' => Purify::clean($data['url'], self::$generalFieldsConfig),
            'meta_title' => Purify::clean($data['meta_title'], self::$generalFieldsConfig),
            'meta_description' => Purify::clean($data['meta_description'], self::$generalFieldsConfig),
            'meta_keywords' => Purify::clean($data['meta_keywords'], self::$generalFieldsConfig)
        ];
    }
}
