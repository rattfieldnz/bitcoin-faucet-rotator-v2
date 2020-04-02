<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class PaymentProcessor
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string|null $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property Carbon|null $deleted_at
 * @property-read Collection|Faucet[] $faucets
 * @property-read int|null $faucets_count
 * @method static Builder|PaymentProcessor findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static Builder|PaymentProcessor newModelQuery()
 * @method static Builder|PaymentProcessor newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentProcessor onlyTrashed()
 * @method static Builder|PaymentProcessor query()
 * @method static bool|null restore()
 * @method static Builder|PaymentProcessor whereCreatedAt($value)
 * @method static Builder|PaymentProcessor whereDeletedAt($value)
 * @method static Builder|PaymentProcessor whereId($value)
 * @method static Builder|PaymentProcessor whereMetaDescription($value)
 * @method static Builder|PaymentProcessor whereMetaKeywords($value)
 * @method static Builder|PaymentProcessor whereMetaTitle($value)
 * @method static Builder|PaymentProcessor whereName($value)
 * @method static Builder|PaymentProcessor whereSlug($value)
 * @method static Builder|PaymentProcessor whereUpdatedAt($value)
 * @method static Builder|PaymentProcessor whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|PaymentProcessor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentProcessor withoutTrashed()
 * @mixin Model
 */
class PaymentProcessor extends Model
{
    use SoftDeletes, Sluggable;

    public $table = 'payment_processors';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'url',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'url' => 'string',
        'slug' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_keywords' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'min:1|max:25|required|unique:payment_processors,name',
        'url' => [
            'required',
            'url',
            'active_url',
            'unique:payment_processors,url',
            'max:150',
            'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/' //URL must be http or https
        ],
        'meta_title' => 'string|max:70',
        'meta_description' => 'string|max:160',
        'meta_keywords' => 'string|max:255'
    ];

    protected static $logAttributes = ['name', 'url', 'meta_title', 'meta_description', 'meta_keywords'];

    protected static $logOnlyDirty = true;

    /**
     * @return BelongsToMany
     **/
    public function faucets()
    {
        return $this->belongsToMany(Faucet::class, 'faucet_payment_processor');
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

    public function isDeleted()
    {
        if ($this->attributes['deleted_at']) {
            return true;
        }
        return false;
    }
}
