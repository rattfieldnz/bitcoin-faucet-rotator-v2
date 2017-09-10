<?php

namespace App\Transformers;

use App\Helpers\Functions\Faucets;
use App\Helpers\Functions\Http;
use App\Models\Faucet;
use App\Helpers\Functions\Users;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class FaucetsTransformer
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package namespace App\Transformers;
 */
class FaucetsTransformer extends TransformerAbstract
{

    /**
     * Transform the Faucet entity
     *
     * @param \App\Models\User $user
     * @param \App\Models\Faucet $model
     * @param bool $addPaymentProcessors
     *
     * @return array
     */
    public function transform(User $user, Faucet $model, $addPaymentProcessors = false)
    {
        $referralCode = Faucets::getUserFaucetRefCode($user, $model);
        $faucet = [
            'name' => $model->name,
            'slug' => $model->slug,
            'url' => $model->url . $referralCode,
            'can_show_in_iframes' => (boolean)Http::canShowInIframes($model->url),
            'interval_minutes' => (int)$model->interval_minutes,
            'min_payout' => (int)$model->min_payout,
            'max_payout' => (int)$model->max_payout,
            'has_ref_program' => (boolean)$model->has_ref_program,
            'ref_payout_percent' => (double)$model->ref_payout_percent,
            'comments' => $model->comments,
            'is_paused' => (boolean)$model->is_paused,
            'meta_title' => $model->meta_title,
            'meta_description' => $model->meta_description,
            'meta_keywords' => $model->meta_keywords,
            'user_slug' => $user->slug
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
