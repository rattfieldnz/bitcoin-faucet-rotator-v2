<?php

namespace App\Transformers;

use App\Models\PaymentProcessor;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class PaymentProcessorsTransformer
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package namespace App\Transformers;
 */
class PaymentProcessorsTransformer extends TransformerAbstract
{

    /**
     * Transform the \PaymentProcessor entity
     *
     * @param PaymentProcessor $model
     *
     * @return array
     */
    public function transform(PaymentProcessor $model, $addFaucets = false)
    {
        $paymentProcessor = [
            'name' => $model->name,
            'url' => $model->url
        ];


        if ($addFaucets == true) {
            $faucets = $model->faucets()->get();
            for ($i = 0; $i < count($faucets); $i++) {
                $faucets[$i] = (new FaucetsTransformer)->transform($faucets[$i]);
            }
            $paymentProcessor['faucets'] = $faucets;
        }

        return $paymentProcessor;
    }

    public function transformMultiple(Collection $models)
    {
        $collection = collect();

        foreach ($models as $m) {
            $collection->push($this->transform($m));
        }

        return $collection;
    }
}
