<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Laratrust\LaratrustRole;

class Role extends LaratrustRole
{
    use Sluggable;


    public $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'display_name' => 'string',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'min:5|max:50|required|unique:roles,name',
        'display_name' => 'min:5|max:100|required',
        'description' => 'min:5|max:255|required',
    ];

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
}
