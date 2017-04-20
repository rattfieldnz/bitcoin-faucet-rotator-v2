<?php

use Faker\Factory as Faker;
use App\Models\PaymentProcessor;
use App\Repositories\PaymentProcessorRepository;

trait MakePaymentProcessorTrait
{
    /**
     * Create fake instance of PaymentProcessor and save it in database
     *
     * @param array $paymentProcessorFields
     * @return PaymentProcessor
     */
    public function makePaymentProcessor($paymentProcessorFields = [])
    {
        /** @var PaymentProcessorRepository $paymentProcessorRepo */
        $paymentProcessorRepo = App::make(PaymentProcessorRepository::class);
        $theme = $this->fakePaymentProcessorData($paymentProcessorFields);
        return $paymentProcessorRepo->create($theme);
    }

    /**
     * Get fake instance of PaymentProcessor
     *
     * @param array $paymentProcessorFields
     * @return PaymentProcessor
     */
    public function fakePaymentProcessor($paymentProcessorFields = [])
    {
        return new PaymentProcessor($this->fakePaymentProcessorData($paymentProcessorFields));
    }

    /**
     * Get fake data of PaymentProcessor
     *
     * @param array $postFields
     * @return array
     */
    public function fakePaymentProcessorData($paymentProcessorFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'url' => $fake->word,
            'slug' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'meta_title' => $fake->word,
            'meta_description' => $fake->word,
            'meta_keywords' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s')
        ], $paymentProcessorFields);
    }
}
