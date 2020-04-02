<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/03/2017
 * Time: 18:43
 */

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

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
     * @param bool $trashed
     * @param array $columns
     *
     * @return mixed
     * @throws RepositoryException
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
     * @throws RepositoryException
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

    /**
     * Below function is taken from InfyomLabs/laravel-generator v6.0-dev-master.
     * @param $model
     * @param $attributes
     * @see https://github.com/InfyOmLabs/laravel-generator/blob/6.0/src/Common/BaseRepository.php.
     * @return mixed
     */
    public function updateRelations($model, $attributes)
    {
        foreach ($attributes as $key => $val) {
            if (isset($model) &&
                method_exists($model, $key) &&
                is_a(@$model->$key(), Relation::class)
            ) {
                $methodClass = get_class($model->$key($key));
                switch ($methodClass) {
                    case BelongsToMany::class:
                        $new_values = Arr::get($attributes, $key, []);
                        if (array_search('', $new_values) !== false) {
                            unset($new_values[array_search('', $new_values)]);
                        }
                        $model->$key()->sync(array_values($new_values));
                        break;
                    case BelongsTo::class:
                        $model_key = $model->$key()->getQualifiedForeignKeyName();
                        $new_value = Arr::get($attributes, $key, null);
                        $new_value = $new_value == '' ? null : $new_value;
                        $model->$model_key = $new_value;
                        break;
                    case HasOne::class:
                        break;
                    case HasOneOrMany::class:
                        break;
                    case HasMany::class:
                        $new_values = Arr::get($attributes, $key, []);
                        if (array_search('', $new_values) !== false) {
                            unset($new_values[array_search('', $new_values)]);
                        }

                        list($temp, $model_key) = explode('.', $model->$key($key)->getQualifiedForeignKeyName());

                        foreach ($model->$key as $rel) {
                            if (!in_array($rel->id, $new_values)) {
                                $rel->$model_key = null;
                                $rel->save();
                            }
                            unset($new_values[array_search($rel->id, $new_values)]);
                        }

                        if (count($new_values) > 0) {
                            $related = get_class($model->$key()->getRelated());
                            foreach ($new_values as $val) {
                                $rel = $related::find($val);
                                $rel->$model_key = $model->id;
                                $rel->save();
                            }
                        }
                        break;
                }
            }
        }

        return $model;
    }
}
