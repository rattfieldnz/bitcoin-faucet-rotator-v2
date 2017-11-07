<?php

namespace App\Models;

use App\Models\AlertIcon;
use App\Models\AlertType;
use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Alert
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 */
class Alert extends Model
{
    use SoftDeletes;
    use Sluggable;

    public $table = 'alerts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'alert_type_id',
        'alert_icon_id',
        'hide_alert',
        'show_site_wide',
        'show_only_on_home_page',
        'sent_with_twitter',
        'publish_at',
        'hide_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'summary' => 'string',
        'content' => 'string',
        'alert_type_id' => 'integer',
        'alert_icon_id' => 'integer',
        'hide_alert' => 'boolean',
        'show_site_wide' => 'boolean',
        'show_only_on_home_page' => 'boolean',
        'sent_with_twitter' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'min:5|max:100|required|unique:alerts,title',
        'summary' => 'min:25|max:255|required',
        'content' => 'min:200|max:16777215|required',
        'keywords' => 'sometimes|string|max:255',
        'alert_type_id' => 'required|integer|exists:alert_types,id',
        'alert_icon_id' => 'required|integer|exists:alert_icons,id',
        'hide_alert' => 'sometimes|boolean',
        'show_site_wide' => 'sometimes|boolean',
        'show_only_on_home_page' => 'sometimes|boolean',
        'sent_with_twitter' => 'sometimes|boolean',
        'publish_at' => 'sometimes|date|date_format:Y-m-d H:i:s',
        'hide_at' => 'sometimes|date|date_format:Y-m-d H:i:s',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function alertIcon()
    {
        return $this->belongsTo(AlertIcon::class, 'alert_icon_id', 'id)');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function alertType()
    {
        return $this->belongsTo(AlertType::class, 'alert_type_id', 'id');
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
                'source' => 'title'
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
