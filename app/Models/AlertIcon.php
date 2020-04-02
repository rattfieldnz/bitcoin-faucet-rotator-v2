<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class AlertIcon
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $icon_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Alert[] $alerts
 * @property-read int|null $alerts_count
 * @method static bool|null forceDelete()
 * @method static Builder|AlertIcon newModelQuery()
 * @method static Builder|AlertIcon newQuery()
 * @method static \Illuminate\Database\Query\Builder|AlertIcon onlyTrashed()
 * @method static Builder|AlertIcon query()
 * @method static bool|null restore()
 * @method static Builder|AlertIcon whereCreatedAt($value)
 * @method static Builder|AlertIcon whereDeletedAt($value)
 * @method static Builder|AlertIcon whereIconClass($value)
 * @method static Builder|AlertIcon whereId($value)
 * @method static Builder|AlertIcon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AlertIcon withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AlertIcon withoutTrashed()
 * @mixin Model
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
     * @return HasMany
     **/
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
