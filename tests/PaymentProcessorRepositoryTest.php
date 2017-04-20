<?php

use App\Models\PaymentProcessor;
use App\Repositories\PaymentProcessorRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentProcessorRepositoryTest extends TestCase
{
    use MakePaymentProcessorTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentProcessorRepository
     */
    protected $paymentProcessorRepo;

    public function setUp()
    {
        parent::setUp();
        $this->paymentProcessorRepo = App::make(PaymentProcessorRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePaymentProcessor()
    {
        $paymentProcessor = $this->fakePaymentProcessorData();
        $createdPaymentProcessor = $this->paymentProcessorRepo->create($paymentProcessor);
        $createdPaymentProcessor = $createdPaymentProcessor->toArray();
        $this->assertArrayHasKey('id', $createdPaymentProcessor);
        $this->assertNotNull($createdPaymentProcessor['id'], 'Created PaymentProcessor must have id specified');
        $this->assertNotNull(PaymentProcessor::find($createdPaymentProcessor['id']), 'PaymentProcessor with given id must be in DB');
        $this->assertModelData($paymentProcessor, $createdPaymentProcessor);
    }

    /**
     * @test read
     */
    public function testReadPaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $dbPaymentProcessor = $this->paymentProcessorRepo->find($paymentProcessor->id);
        $dbPaymentProcessor = $dbPaymentProcessor->toArray();
        $this->assertModelData($paymentProcessor->toArray(), $dbPaymentProcessor);
    }

    /**
     * @test update
     */
    public function testUpdatePaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $fakePaymentProcessor = $this->fakePaymentProcessorData();
        $updatedPaymentProcessor = $this->paymentProcessorRepo->update($fakePaymentProcessor, $paymentProcessor->id);
        $this->assertModelData($fakePaymentProcessor, $updatedPaymentProcessor->toArray());
        $dbPaymentProcessor = $this->paymentProcessorRepo->find($paymentProcessor->id);
        $this->assertModelData($fakePaymentProcessor, $dbPaymentProcessor->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePaymentProcessor()
    {
        $paymentProcessor = $this->makePaymentProcessor();
        $resp = $this->paymentProcessorRepo->delete($paymentProcessor->id);
        $this->assertTrue($resp);
        $this->assertNull(PaymentProcessor::find($paymentProcessor->id), 'PaymentProcessor should not exist in DB');
    }
}
