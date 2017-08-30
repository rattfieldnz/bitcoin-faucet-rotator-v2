<?php

namespace App\Transformers;

use App\Helpers\Functions\Faucets;
use App\Models\Faucet;
use App\Models\Role;
use App\Models\User;
use Helpers\Functions\Users;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class FaucetsTransformer
 *
 * @package namespace App\Transformers;
 */
class FaucetsTransformer extends TransformerAbstract
{

    /**
     * Transform the Faucet entity
     *
     * @param Faucet $model
     * @param bool   $addPaymentProcessors
     *
     * @return array
     */
    public function transform(Faucet $model, $addPaymentProcessors = false)
    {
        $user = Users::adminUser();

        $referralCode = Faucets::getUserFaucetRefCode($user, $model);
        $faucet = [
            'name' => $model->name,
            'slug' => $model->slug,
            'url' => $model->url . $referralCode,
            'interval_minutes' => (int)$model->interval_minutes,
            'min_payout' => (int)$model->min_payout,
            'max_payout' => (int)$model->max_payout,
            'has_ref_program' => (boolean)$model->has_ref_program,
            'ref_payout_percent' => (double)$model->ref_payout_percent,
            'comments' => $model->comments,
            'is_paused' => (boolean)$model->is_paused,
            'meta_title' => $model->meta_title,
            'meta_description' => $model->meta_description,
            'meta_keywords' => $model->meta_keywords
        ];

        if ($addPaymentProcessors == true) {
            $paymentProcessors = $model->paymentProcessors()->get();
            for ($i = 0; $i < count($paymentProcessors); $i++) {
                $paymentProcessors[$i] = (new PaymentProcessorsTransformer)->transform($paymentProcessors[$i]);
            }
            $faucet['payment_processors'] = $paymentProcessors;
        }

        return $faucet;
    }

    public function transformMultiple(Collection $models)
    {
        $collection = collect();

        foreach ($models as $m) {
            $collection->push($this->transform($m, false));
        }

        return $collection;
    }
}
