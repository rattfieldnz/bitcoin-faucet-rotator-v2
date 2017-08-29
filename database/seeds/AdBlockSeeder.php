<?php

use App\Models\AdBlock;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdBlockSeeder extends BaseSeeder
{
    public function run()
    {
        AdBlock::truncate();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/ad_block.csv');

        foreach ($data as $d) {
            try {
                $adBlock = new AdBlock([
                    'ad_content' => Purifier::clean($d['ad_content']),
                    'user_id' => (int)User::where('is_admin', '=', true)->firstOrFail()->id
                ]);

                $adBlock->save();
            } catch (Exception $e) {
                error_log($e->getMessage() . "<br>" . 'The adblock did not save sucessfully, please check error logs for more information.');
            }
        }
    }
}
