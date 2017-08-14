<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class PrivacyPolicy
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
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
