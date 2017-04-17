<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/03/2017
 * Time: 18:43
 */

namespace App\Repositories;


use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityDeleted;

abstract class Repository extends BaseRepository
{

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

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @param bool $permanent
     * @return int
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();
        $deleted = null;

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $deleted = $this->model->delete();

        event(new RepositoryEntityDeleted($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }

    public function restoreDeleted($slug){
        $model = $this->model->onlyTrashed()->where('slug', $slug)->first();
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $restoredModel = $model->restore();
        $this->skipPresenter($temporarySkipPresenter);

        return $this->parserResult($restoredModel);

    }

    public function withTrashed(){
        return $this->model->withTrashed();
    }

}