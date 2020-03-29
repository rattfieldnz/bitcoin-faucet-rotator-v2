<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AlertIcon
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $icon_class
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Alert[] $alerts
 * @property-read int|null $alerts_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertIcon onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AlertIcon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertIcon withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AlertIcon withoutTrashed()
 * @mixin \Eloquent
 */
class AlertIcon extends Model
{
    use SoftDeletes;

    public $table = 'alert_icons';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'icon_class'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'icon_class' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'icon_class' => 'min:1|max:50|required|unique:alert_icons,icon_class',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
