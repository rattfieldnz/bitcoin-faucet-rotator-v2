<?php
use App\Models\PaymentProcessor;

/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 03-Mar-2015
 * Time: 13:41
 */

class PaymentProcessorsTableSeeder extends BaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentProcessor::truncate();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/payment_processors.csv');

        foreach ($data as $d) {
            try {
                $paymentProcessor = new PaymentProcessor([
                    'name' => Purifier::clean($d['name'], 'generalFields'),
                    'url' => Purifier::clean($d['url'], 'generalFields'),
                    'meta_title' => Purifier::clean($d['meta_title'], 'generalFields'),
                    'meta_description' => Purifier::clean($d['meta_description'], 'generalFields'),
                    'meta_keywords' => Purifier::clean($d['meta_keywords'], 'generalFields')
                ]);

                $paymentProcessor->save();
                $this->command->info(
                    "Seeded Payment Processor => ID:  " . $paymentProcessor->id .
                    ", Name: " . $paymentProcessor->name
                );
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
