<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentProcessor
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
