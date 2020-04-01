<?php

namespace App\Repositories;

use App\Models\Faucet;
use Stevebauman\Purify\Facades\Purify;

/**
 * Class FaucetRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
class FaucetRepository extends Repository implements IRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'url',
        'interval_minutes',
        'min_payout',
        'max_payout',
        'has_ref_program',
        'ref_payout_percent',
        'comments',
        'is_paused',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'has_low_balance'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Faucet::class;
    }

    /**
     * Create a new faucet.
     *
     * @param  array $data
     * @return Faucet
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $faucetData = self::cleanInput($data);
        $faucet = parent::create($faucetData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($faucet, $faucetData);
        $faucet->save();
        return $this->parserResult($faucet);
    }

    /**
     * Update the specified faucet.
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
        $faucetData = self::cleanInput($data);
        $faucet = Faucet::where('id', $id)->withTrashed()->first();
        $updatedFaucet = $faucet->fill($faucetData);
        $this->skipPresenter($temporarySkipPresenter);
        $faucet = $this->updateRelations($updatedFaucet, $faucetData);
        $updatedFaucet->save();
        return $this->parserResult($updatedFaucet);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @param bool  $deleted
     *
     * @return mixed
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
     * Sanitize input / faucet data.
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
            'interval_minutes' => Purify::clean($data['interval_minutes'], self::$generalFieldsConfig),
            'min_payout' => Purify::clean($data['min_payout'], self::$generalFieldsConfig),
            'max_payout' => Purify::clean($data['max_payout'], self::$generalFieldsConfig),
            'has_ref_program' => Purify::clean($data['has_ref_program'], self::$generalFieldsConfig),
            'ref_payout_percent' => Purify::clean($data['ref_payout_percent'], self::$generalFieldsConfig),
            'comments' => Purify::clean($data['comments'], self::$generalFieldsConfig),
            'is_paused' => Purify::clean($data['is_paused'], self::$generalFieldsConfig),
            'meta_title' => Purify::clean($data['meta_title'], self::$generalFieldsConfig),
            'meta_description' => Purify::clean($data['meta_description'], self::$generalFieldsConfig),
            'meta_keywords' => Purify::clean($data['meta_keywords'], self::$generalFieldsConfig),
            'has_low_balance'  => Purify::clean($data['has_low_balance'], self::$generalFieldsConfig),
            'twitter_message' => Purify::clean($data['twitter_message'], self::$generalFieldsConfig)
        ];
    }
}
