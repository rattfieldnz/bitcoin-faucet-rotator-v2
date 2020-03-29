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
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $content
 * @property string|null $keywords
 * @property int $alert_type_id
 * @property int $alert_icon_id
 * @property bool $hide_alert
 * @property bool|null $sent_with_twitter
 * @property string|null $twitter_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AlertIcon $alertIcon
 * @property-read \App\Models\AlertType $alertType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Alert onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereAlertIconId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereAlertTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereHideAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereSentWithTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereTwitterMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Alert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Alert withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Alert withoutTrashed()
 * @mixin \Eloquent
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
        'keywords',
        'alert_type_id',
        'alert_icon_id',
        'hide_alert',
        'sent_with_twitter',
        'twitter_message',
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
        'keywords' => 'string',
        'alert_type_id' => 'integer',
        'alert_icon_id' => 'integer',
        'hide_alert' => 'boolean',
        'sent_with_twitter' => 'boolean',
        'twitter_message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'min:5|max:100|required|unique:alerts,title',
        'summary' => 'required|min:25|max:255',
        'content' => 'required|min:200|max:16777215',
        'keywords' => 'sometimes|string|max:255',
        'alert_type_id' => 'required|integer|exists:alert_types,id',
        'alert_icon_id' => 'required|integer|exists:alert_icons,id',
        'hide_alert' => 'sometimes|boolean',
        'sent_with_twitter' => 'sometimes|boolean',
        'twitter_message' => 'required_if:sent_with_twitter,==,1|string|min:10|max:255',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function alertIcon()
    {
        return $this->belongsTo(AlertIcon::class, 'alert_icon_id', 'id');
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
