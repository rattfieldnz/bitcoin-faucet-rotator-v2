<?php

use App\Models\MainMeta;

class MainMetaTableSeeder extends BaseSeeder
{
    public function run()
    {
        MainMeta::truncate();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/main_meta.csv');

        foreach ($data as $d) {
            try {
                $mainMeta = new MainMeta([
                    'title' => Purifier::clean($d['title'], 'generalFields'),
                    'description' => Purifier::clean($d['description'], 'generalFields'),
                    'keywords' => Purifier::clean($d['keywords'], 'generalFields'),
                    'google_analytics_id' => "",
                    'yandex_verification' => "",
                    'bing_verification' => "",
                    'page_main_title' => Purifier::clean($d['page_main_title'], 'generalFields'),
                    'page_main_content' => Purifier::clean($d['page_main_content']),
                    'addthisid' => "",
                    'twitter_username' => "",
                    'feedburner_feed_url' => "",
                    'disqus_shortname' => "",
                    'prevent_adblock_blocking' => true
                ]);

                $mainMeta->save();
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
