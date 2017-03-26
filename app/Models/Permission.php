<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Laratrust\LaratrustPermission;

class Permission extends LaratrustPermission
{
    use Sluggable;

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
