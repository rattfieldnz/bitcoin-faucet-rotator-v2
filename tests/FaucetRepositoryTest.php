<?php

use App\Models\Faucet;
use App\Repositories\FaucetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FaucetRepositoryTest extends TestCase
{
    use MakeFaucetTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FaucetRepository
     */
    protected $faucetRepo;

    public function setUp()
    {
        parent::setUp();
        $this->faucetRepo = App::make(FaucetRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFaucet()
    {
        $faucet = $this->fakeFaucetData();
        $createdFaucet = $this->faucetRepo->create($faucet);
        $createdFaucet = $createdFaucet->toArray();
        $this->assertArrayHasKey('id', $createdFaucet);
        $this->assertNotNull($createdFaucet['id'], 'Created Faucet must have id specified');
        $this->assertNotNull(Faucet::find($createdFaucet['id']), 'Faucet with given id must be in DB');
        $this->assertModelData($faucet, $createdFaucet);
    }

    /**
     * @test read
     */
    public function testReadFaucet()
    {
        $faucet = $this->makeFaucet();
        $dbFaucet = $this->faucetRepo->find($faucet->id);
        $dbFaucet = $dbFaucet->toArray();
        $this->assertModelData($faucet->toArray(), $dbFaucet);
    }

    /**
     * @test update
     */
    public function testUpdateFaucet()
    {
        $faucet = $this->makeFaucet();
        $fakeFaucet = $this->fakeFaucetData();
        $updatedFaucet = $this->faucetRepo->update($fakeFaucet, $faucet->id);
        $this->assertModelData($fakeFaucet, $updatedFaucet->toArray());
        $dbFaucet = $this->faucetRepo->find($faucet->id);
        $this->assertModelData($fakeFaucet, $dbFaucet->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFaucet()
    {
        $faucet = $this->makeFaucet();
        $resp = $this->faucetRepo->delete($faucet->id);
        $this->assertTrue($resp);
        $this->assertNull(Faucet::find($faucet->id), 'Faucet should not exist in DB');
    }
}
