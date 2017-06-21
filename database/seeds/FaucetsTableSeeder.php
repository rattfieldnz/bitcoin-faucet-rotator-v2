<?php
use App\Models\Faucet;
use App\Models\User;

/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 02-Mar-2015
 * Time: 22:38
 */

class FaucetsTableSeeder extends BaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/faucets.csv', ';');
        $user = User::where('user_name', env('ADMIN_USERNAME'))->first();

        foreach ($data as $d) {
            $url = Purifier::clean($d['url'], 'generalFields');
            try {
                $faucet = new Faucet([
                    'name' => Purifier::clean($d['name'], 'generalFields'),
                    'url' => $url,
                    'interval_minutes' => (int)Purifier::clean($d['interval_minutes'], 'generalFields'),
                    'min_payout' => (int)Purifier::clean($d['min_payout'], 'generalFields'),
                    'max_payout' => (int)Purifier::clean($d['max_payout'], 'generalFields'),
                    'has_ref_program' => (int)Purifier::clean($d['has_ref_program'], 'generalFields'),
                    'ref_payout_percent' => (int)Purifier::clean($d['ref_payout_percent'], 'generalFields'),
                    'comments' => Purifier::clean($d['comments'], 'generalFields'),
                    'is_paused' => (int)Purifier::clean($d['is_paused'], 'generalFields'),
                    'meta_title' => Purifier::clean($d['meta_title'], 'generalFields'),
                    'meta_description' => Purifier::clean($d['meta_description'], 'generalFields'),
                    'meta_keywords' => Purifier::clean($d['meta_keywords'], 'generalFields'),
                    'has_low_balance' => (int)Purifier::clean($d['has_low_balance'], 'generalFields'),
                ]);
                $faucet->save();
                $this->command->info(
                    "Seeding Faucet => Name: " . $faucet->name .
                    ", ID: " . $faucet->id .
                    ", URL: " . $faucet->url
                );

                $referralCode = Purifier::clean($d['referral_code'], 'generalFields');
                $faucet->users()->attach($user->id, ['faucet_id' => $faucet->id, 'referral_code' => $referralCode]);
                $this->command->info(
                    "Seeding Admin-Faucet Referral Info => User ID: " . $user->id .
                    ", Faucet ID:  " . $faucet->id .
                    ", Faucet Name: " . $faucet->name .
                    ", Referral Code: " . $referralCode
                );
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
