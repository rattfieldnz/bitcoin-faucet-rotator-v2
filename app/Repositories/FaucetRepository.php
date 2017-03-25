<?php

namespace App\Repositories;

use App\Models\Faucet;
use InfyOm\Generator\Common\BaseRepository;
use Mews\Purifier\Facades\Purifier;
use Prettus\Repository\Events\RepositoryEntityDeleted;

class FaucetRepository extends BaseRepository implements IRepository
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
     * Create a new user instance after a valid registration.
     *
     * NOTE: New users cannot register as an admin.
     *
     * @param  array  $data
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

    public function update(array $data, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $faucetData = self::cleanInput($data);
        $faucet = parent::update($faucetData, $id);
        $this->skipPresenter($temporarySkipPresenter);
        $faucet = $this->updateRelations($faucet, $faucetData);
        $faucet->save();
        return $this->parserResult($faucet);
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @param bool $permanent
     * @return int
     */
    public function deleteWhere(array $where, $permanent = false)
    {
        $this->applyScope();
        $deleted = null;

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        if($permanent == true){
            $deleted = $this->model->forceDelete();
        }
        $deleted = $this->model->delete();

        event(new RepositoryEntityDeleted($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }

    public function restoreDeleted($slug){
        $faucet = Faucet::onlyTrashed()->where('slug', $slug)->first();
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $restoredFaucet = $faucet->restore();
        $this->skipPresenter($temporarySkipPresenter);

        return $this->parserResult($restoredFaucet);

    }

    static function cleanInput(array $data)
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
            'is_paused' => Purifier::clean($data['comments'], 'generalFields'),
            'meta_title' => Purifier::clean($data['meta_title'], 'generalFields'),
            'meta_description' => Purifier::clean($data['meta_description'], 'generalFields'),
            'meta_keywords' => Purifier::clean($data['meta_keywords'], 'generalFields'),
            'has_low_balance'  => Purifier::clean($data['has_low_balance'], 'generalFields')
        ];
    }

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $trashed = false, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        if($trashed = true){
            $model = $this->model->withTrashed()->where($field, '=', $value)->get($columns);
            $this->resetModel();

            return $this->parserResult($model);
        }
        $model = $this->model->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function withTrashed(){
        return Faucet::withTrashed();
    }
}
