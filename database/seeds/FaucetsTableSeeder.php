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
        Faucet::truncate();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/faucets.csv', ';');
        $user = User::where('user_name', 'admin')->first();
        $standardUser = User::where('slug', '=', 'bobisbob')->first();

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

                $faucet->twitter_message = "Earn between [faucet_min_payout] and [faucet_max_payout] " .
                                            "satoshis every [faucet_interval] minute/s from [faucet_url] " .
                                            "for free :) #FreeBitcoin #Bitcoin #" .
                                            str_replace(" ", "", ucwords($faucet->name)) . " .";

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

                if (!empty($standardUser) && env('APP_ENV') == 'local') {
                    $standardUserRefCode = 'bobisbob';
                    $faucet->users()->attach($standardUser->id, ['faucet_id' => $faucet->id, 'referral_code' => $standardUserRefCode]);
                    $this->command->info(
                        "Seeding standard User-Faucet Referral Info => User ID: " . $standardUser->id .
                        ", Faucet ID:  " . $faucet->id .
                        ", Faucet Name: " . $faucet->name .
                        ", Referral Code: " . $referralCode
                    );
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
