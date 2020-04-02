<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class AdBlock
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $ad_content
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock newQuery()
 * @method static Builder|AdBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereAdContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdBlock whereUserId($value)
 * @method static Builder|AdBlock withTrashed()
 * @method static Builder|AdBlock withoutTrashed()
 * @mixin Model
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
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
