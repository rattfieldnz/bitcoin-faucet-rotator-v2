<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdBlock
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $ad_content
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereAdContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdBlock whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdBlock withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdBlock withoutTrashed()
 * @mixin \Eloquent
 */
class AdBlock extends Model
{
    use SoftDeletes;

    public $table = 'ad_block';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'ad_content',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ad_content' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ad_content' => 'string',
        'user_id' => 'required|integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
