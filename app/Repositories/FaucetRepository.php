<?php

namespace App\Repositories;

use App\Models\Faucet;
use Mews\Purifier\Facades\Purifier;

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
            'name' => Purifier::clean($data['name'], 'generalFields'),
            'url' => Purifier::clean($data['url'], 'generalFields'),
            'interval_minutes' => Purifier::clean($data['interval_minutes'], 'generalFields'),
            'min_payout' => Purifier::clean($data['min_payout'], 'generalFields'),
            'max_payout' => Purifier::clean($data['max_payout'], 'generalFields'),
            'has_ref_program' => Purifier::clean($data['has_ref_program'], 'generalFields'),
            'ref_payout_percent' => Purifier::clean($data['ref_payout_percent'], 'generalFields'),
            'comments' => Purifier::clean($data['comments'], 'generalFields'),
            'is_paused' => Purifier::clean($data['is_paused'], 'generalFields'),
            'meta_title' => Purifier::clean($data['meta_title'], 'generalFields'),
            'meta_description' => Purifier::clean($data['meta_description'], 'generalFields'),
            'meta_keywords' => Purifier::clean($data['meta_keywords'], 'generalFields'),
            'has_low_balance'  => Purifier::clean($data['has_low_balance'], 'generalFields'),
            'twitter_message' => Purifier::clean($data['twitter_message'], 'generalFields')
        ];
    }
}
