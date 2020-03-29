<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TermsAndConditions whereUpdatedAt($value)
 * @mixin \Eloquent
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
