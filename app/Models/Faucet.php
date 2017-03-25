<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Faucet",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="url",
 *          description="url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="interval_minutes",
 *          description="interval_minutes",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="min_payout",
 *          description="min_payout",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="max_payout",
 *          description="max_payout",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="has_ref_program",
 *          description="has_ref_program",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="ref_payout_percent",
 *          description="ref_payout_percent",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="comments",
 *          description="comments",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_paused",
 *          description="is_paused",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="meta_title",
 *          description="meta_title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="meta_description",
 *          description="meta_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="meta_keywords",
 *          description="meta_keywords",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="has_low_balance",
 *          description="has_low_balance",
 *          type="boolean"
 *      )
 * )
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
        'has_low_balance'
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
        'has_low_balance' => 'boolean'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function paymentProcessors()
    {
        return $this->belongsToMany(\App\Models\PaymentProcessor::class, 'faucet_payment_processor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'referral_info')->withPivot('user_id', 'faucet_id', 'referral_code');
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

    public function isDeleted(){
        if ($this->attributes['deleted_at']) {
            return true;
        }
        return false;
    }
}
