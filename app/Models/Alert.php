<?php

namespace App\Models;

use App\Models\AlertIcon;
use App\Models\AlertType;
use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read \App\Models\AlertIcon $alertIcon
 * @property-read \App\Models\AlertType $alertType
 * @method static Builder|Alert findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static Builder|Alert newModelQuery()
 * @method static Builder|Alert newQuery()
 * @method static \Illuminate\Database\Query\Builder|Alert onlyTrashed()
 * @method static Builder|Alert query()
 * @method static bool|null restore()
 * @method static Builder|Alert whereAlertIconId($value)
 * @method static Builder|Alert whereAlertTypeId($value)
 * @method static Builder|Alert whereContent($value)
 * @method static Builder|Alert whereCreatedAt($value)
 * @method static Builder|Alert whereDeletedAt($value)
 * @method static Builder|Alert whereHideAlert($value)
 * @method static Builder|Alert whereId($value)
 * @method static Builder|Alert whereKeywords($value)
 * @method static Builder|Alert whereSentWithTwitter($value)
 * @method static Builder|Alert whereSlug($value)
 * @method static Builder|Alert whereSummary($value)
 * @method static Builder|Alert whereTitle($value)
 * @method static Builder|Alert whereTwitterMessage($value)
 * @method static Builder|Alert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Alert withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Alert withoutTrashed()
 * @mixin Model
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
     * @return BelongsTo
     **/
    public function alertIcon()
    {
        return $this->belongsTo(AlertIcon::class, 'alert_icon_id', 'id');
    }

    /**
     * @return BelongsTo
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
