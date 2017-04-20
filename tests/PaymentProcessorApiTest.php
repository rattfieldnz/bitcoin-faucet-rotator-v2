<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentProcessorApiTest extends TestCase
{
    use MakePaymentProcessorTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePaymentProcessor()
    {
        $paymentProcessor = $this->fakePaymentProcessorData();
        $this->json('POST', '/api/v1/paymentProcessors', $paymentProcessor);

        $this->assertApiResponse($paymentProcessor);
    }

    /**
     * @test
     */
    public function testReadPaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $this->json('GET', '/api/v1/paymentProcessors/'.$paymentProcessor->id);

        $this->assertApiResponse($paymentProcessor->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $editedPaymentProcessor = $this->fakePaymentProcessorData();

        $this->json('PUT', '/api/v1/paymentProcessors/'.$paymentProcessor->id, $editedPaymentProcessor);

        $this->assertApiResponse($editedPaymentProcessor);
    }

    /**
     * @test
     */
    public function testDeletePaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $this->json('DELETE', '/api/v1/paymentProcessors/'.$paymentProcessor->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/paymentProcessors/'.$paymentProcessor->id);

        $this->assertResponseStatus(404);
    }
}
