<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Alert[] $alerts
 * @property-read int|null $alerts_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereBootstrapAlertClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertType withoutTrashed()
 * @mixin \Eloquent
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
