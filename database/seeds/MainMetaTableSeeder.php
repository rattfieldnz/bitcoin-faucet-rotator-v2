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
                    'google_analytics_id' => Purifier::clean(env('GOOGLE_ANALYTICS_ID'), 'generalFields'),
                    'yandex_verification' => Purifier::clean(env('YANDEX_VERIFICATION_ID'), 'generalFields'),
                    'bing_verification' => Purifier::clean(env('BING_VERIFICATION_ID'), 'generalFields'),
                    'page_main_title' => Purifier::clean($d['page_main_title'], 'generalFields'),
                    'page_main_content' => Purifier::clean($d['page_main_content']),
                    'addthisid' => Purifier::clean(env('ADDTHIS_ID'), 'generalFields'),
                    'twitter_username' => Purifier::clean(env('TWITTER_USERNAME'), 'generalFields'),
                    'feedburner_feed_url' => "",
                    'disqus_shortname' => Purifier::clean(env('DISQUS_SHORTNAME'), 'generalFields'),
                    'prevent_adblock_blocking' => true
                ]);

                $mainMeta->save();
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
}
