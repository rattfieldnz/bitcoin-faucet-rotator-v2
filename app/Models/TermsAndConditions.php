<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class TermsAndConditions
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string|null $title
 * @property string|null $short_description
 * @property string $content
 * @property string|null $keywords
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TermsAndConditions newModelQuery()
 * @method static Builder|TermsAndConditions newQuery()
 * @method static Builder|TermsAndConditions query()
 * @method static Builder|TermsAndConditions whereContent($value)
 * @method static Builder|TermsAndConditions whereCreatedAt($value)
 * @method static Builder|TermsAndConditions whereId($value)
 * @method static Builder|TermsAndConditions whereKeywords($value)
 * @method static Builder|TermsAndConditions whereShortDescription($value)
 * @method static Builder|TermsAndConditions whereTitle($value)
 * @method static Builder|TermsAndConditions whereUpdatedAt($value)
 * @mixin Model
 */
class TermsAndConditions extends Model
{

    public $table = 'terms_and_conditions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'short_description',
        'content',
        'keywords'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'short_description' => 'string',
        'content' => 'string',
        'keywords' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'sometimes|string|max:70',
        'short_description' => 'sometimes|string|min:20|max:160',
        'content' => 'required|min:200',
        'keywords' => 'sometimes|max:255'
    ];
}
