<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FaucetApiTest extends TestCase
{
    use MakeFaucetTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFaucet()
    {
        $faucet = $this->fakeFaucetData();
        $this->json('POST', '/api/v1/faucets', $faucet);

        $this->assertApiResponse($faucet);
    }

    /**
     * @test
     */
    public function testReadFaucet()
    {
        $faucet = $this->makeFaucet();
        $this->json('GET', '/api/v1/faucets/'.$faucet->id);

        $this->assertApiResponse($faucet->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFaucet()
    {
        $faucet = $this->makeFaucet();
        $editedFaucet = $this->fakeFaucetData();

        $this->json('PUT', '/api/v1/faucets/'.$faucet->id, $editedFaucet);

        $this->assertApiResponse($editedFaucet);
    }

    /**
     * @test
     */
    public function testDeleteFaucet()
    {
        $faucet = $this->makeFaucet();
        $this->json('DELETE', '/api/v1/faucets/'.$faucet->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/faucets/'.$faucet->id);

        $this->assertResponseStatus(404);
    }
}
