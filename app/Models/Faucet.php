<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Faucet
 *
 * @author Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $interval_minutes
 * @property int $min_payout
 * @property int $max_payout
 * @property bool $has_ref_program
 * @property int $ref_payout_percent
 * @property string|null $comments
 * @property bool $is_paused
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property bool $has_low_balance
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $twitter_message
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PaymentProcessor[] $paymentProcessors
 * @property-read int|null $payment_processors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Faucet onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereHasLowBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereHasRefProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereIntervalMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereIsPaused($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereMaxPayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereMinPayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereRefPayoutPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereTwitterMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Faucet whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Faucet withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Faucet withoutTrashed()
 * @mixin \Eloquent
 */
class Faucet extends Model
{
    use SoftDeletes, Sluggable;

    public $table = 'faucets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'url',
        'interval_minutes',
        'min_payout',
        'max_payout',
        'has_ref_program',
        'ref_payout_percent',
        'comments',
        'is_paused',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'has_low_balance',
        'twitter_message'
    ];

    protected $guarded = [
        'id'
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
        'interval_minutes' => 'integer',
        'min_payout' => 'integer',
        'max_payout' => 'integer',
        'has_ref_program' => 'boolean',
        'comments' => 'string',
        'is_paused' => 'boolean',
        'slug' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'has_low_balance' => 'boolean',
        'twitter_message' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'min:5|max:25|required|unique:faucets,name',
        'url' => [
            'required',
            'url',
            'active_url',
            'unique:faucets,url',
            'max:150',
            'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/' //URL must be http or https
        ],
        'interval_minutes' => 'required|integer|min:0',
        'min_payout' => 'required|numeric|min:0',
        'max_payout' => 'required|numeric|min:0',
        'payment_processors' => 'required|min:1|exists:faucet_payment_processor,payment_processor_id',
        'has_ref_program' => 'required|boolean',
        'ref_payout_percent' => 'required_if:has_ref_program,1|integer|min:0',
        'comments' => 'string|max:255',
        'is_paused' => 'required|boolean',
        'meta_title' => 'sometimes|string|max:70',
        'meta_description'  => 'sometimes|string|max:160',
        'meta_keywords' => 'sometimes|string|max:255',
        'has_low_balance' => 'required|boolean'
    ];

    protected static $logAttributes = [
        'name',
        'url',
        'interval_minutes',
        'min_payout',
        'max_payout',
        'payment_processors',
        'has_ref_program',
        'ref_payout_percent',
        'comments',
        'is_paused',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'has_low_balance'
    ];

    protected static $logOnlyDirty = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function paymentProcessors()
    {
        return $this->belongsToMany(PaymentProcessor::class, 'faucet_payment_processor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(User::class, 'referral_info')->withPivot('user_id', 'faucet_id', 'referral_code');
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

    /**
     * A method to tell user if a faucet
     * has a referral program or not, in
     * a readable format.
     *
     * @return string
     */
    public function hasRefProgram()
    {
        if ($this->attributes['has_ref_program']) {
            return 'Yes';
        }
        return 'No';
    }
    /**
     * A method to tell user if a faucet
     * is paused or not, in
     * a readable format.
     *
     * @return string
     */
    public function status()
    {
        if ($this->attributes['is_paused']) {
            return 'Paused';
        }
        return 'Active';
    }

    public function lowBalanceStatus()
    {
        if ($this->attributes['has_low_balance'] == true) {
            return 'Yes';
        }
        return 'No';
    }
}
