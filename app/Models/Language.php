<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property string $iso_code
 * @property string $name
 * @method static Builder|Language newModelQuery()
 * @method static Builder|Language newQuery()
 * @method static Builder|Language query()
 * @method static Builder|Language whereIsoCode($value)
 * @method static Builder|Language whereName($value)
 * @mixin Eloquent
 */
class Language extends Model
{
    public $table = 'languages';

    public $timestamps = false;

    public $fillable = [
        'name',
        'iso_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'iso_code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'min:2|max:25|required|unique:languages,name',
        'iso_code' => 'min:2|max:10|required|unique:languages,name',
    ];

    public function name()
    {
        return $this->attributes['name'];
    }

    public function isoCode()
    {
        return $this->attributes['iso_code'];
    }
}
