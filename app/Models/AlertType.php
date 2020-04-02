<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class AlertType
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $bootstrap_alert_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Alert[] $alerts
 * @property-read int|null $alerts_count
 * @method static Builder|AlertType findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static Builder|AlertType newModelQuery()
 * @method static Builder|AlertType newQuery()
 * @method static \Illuminate\Database\Query\Builder|AlertType onlyTrashed()
 * @method static Builder|AlertType query()
 * @method static bool|null restore()
 * @method static Builder|AlertType whereBootstrapAlertClass($value)
 * @method static Builder|AlertType whereCreatedAt($value)
 * @method static Builder|AlertType whereDeletedAt($value)
 * @method static Builder|AlertType whereDescription($value)
 * @method static Builder|AlertType whereId($value)
 * @method static Builder|AlertType whereName($value)
 * @method static Builder|AlertType whereSlug($value)
 * @method static Builder|AlertType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AlertType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AlertType withoutTrashed()
 * @mixin Model
 */
class AlertType extends Model
{
    use SoftDeletes;
    use Sluggable;

    public $table = 'alert_types';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'bootstrap_alert_class'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'bootstrap_alert_class' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'min:2|max:20|required|unique:alert_types,name',
        'description' => 'min:10|max:255|required',
        'bootstrap_alert_class' => 'min:3|max:25|required'
    ];

    /**
     * @return HasMany
     **/
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
