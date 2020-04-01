<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/03/2017
 * Time: 18:43
 */

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Config;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class Repository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
abstract class Repository extends BaseRepository
{
    protected static $generalFieldsConfig;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        self::$generalFieldsConfig = Config::get('purify.generalFields');
    }

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $trashed = false, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        if ($trashed = true) {
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
     * @return   int
     * @internal param bool $permanent
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();
        $deleted = null;

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $deleted = $this->model->delete();

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }

    /**
     * Restore a soft-deleted model.
     *
     * @param $slug
     *
     * @return mixed
     */
    public function restoreDeleted($slug)
    {
        $model = $this->model->onlyTrashed()->where('slug', $slug)->first();
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $restoredModel = $model->restore();
        $this->skipPresenter($temporarySkipPresenter);

        return $this->parserResult($restoredModel);
    }

    /**
     * Retrieve all models, including trashed / soft-deleted.
     *
     * @return mixed
     */
    public function withTrashed()
    {
        return $this->model->withTrashed();
    }

    /**
     * Retrieve only trashed / soft-deleted models.
     *
     * @return mixed
     */
    public function onlyTrashed()
    {
        return $this->model->onlyTrashed();
    }
}
