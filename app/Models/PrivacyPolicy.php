<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class PrivacyPolicy
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
 * @method static Builder|PrivacyPolicy newModelQuery()
 * @method static Builder|PrivacyPolicy newQuery()
 * @method static Builder|PrivacyPolicy query()
 * @method static Builder|PrivacyPolicy whereContent($value)
 * @method static Builder|PrivacyPolicy whereCreatedAt($value)
 * @method static Builder|PrivacyPolicy whereId($value)
 * @method static Builder|PrivacyPolicy whereKeywords($value)
 * @method static Builder|PrivacyPolicy whereShortDescription($value)
 * @method static Builder|PrivacyPolicy whereTitle($value)
 * @method static Builder|PrivacyPolicy whereUpdatedAt($value)
 * @mixin Model
 */
class PrivacyPolicy extends Model
{
    public $table = 'privacy_policy';
    
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
