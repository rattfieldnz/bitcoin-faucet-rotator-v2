<?php

use Faker\Factory as Faker;
use App\Models\Faucet;
use App\Repositories\FaucetRepository;

trait MakeFaucetTrait
{
    /**
     * Create fake instance of Faucet and save it in database
     *
     * @param array $faucetFields
     * @return Faucet
     */
    public function makeFaucet($faucetFields = [])
    {
        /** @var FaucetRepository $faucetRepo */
        $faucetRepo = App::make(FaucetRepository::class);
        $theme = $this->fakeFaucetData($faucetFields);
        return $faucetRepo->create($theme);
    }

    /**
     * Get fake instance of Faucet
     *
     * @param array $faucetFields
     * @return Faucet
     */
    public function fakeFaucet($faucetFields = [])
    {
        return new Faucet($this->fakeFaucetData($faucetFields));
    }

    /**
     * Get fake data of Faucet
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFaucetData($faucetFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'url' => $fake->word,
            'interval_minutes' => $fake->randomDigitNotNull,
            'min_payout' => $fake->randomDigitNotNull,
            'max_payout' => $fake->randomDigitNotNull,
            'has_ref_program' => $fake->word,
            'ref_payout_percent' => $fake->word,
            'comments' => $fake->word,
            'is_paused' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'slug' => $fake->word,
            'meta_title' => $fake->word,
            'meta_description' => $fake->word,
            'meta_keywords' => $fake->word,
            'has_low_balance' => $fake->word,
            'deleted_at' => $fake->date('Y-m-d H:i:s')
        ], $faucetFields);
    }
}
