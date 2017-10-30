<?php

use App\Helpers\Functions\Users;
use App\Models\SocialNetworks;
use Illuminate\Database\Seeder;

class SocialLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialNetworks::truncate();

        $adminUser = Users::adminUser();

        $socialLinks = new SocialNetworks([
            'facebook_url' => 'https://www.facebook.com/freebtc.website',
            'twitter_url' => 'https://twitter.com/freebtcwebsite',
            'reddit_url' => 'https://reddit.com/user/freebtcwebsite/',
            'google_plus_url' => 'https://plus.google.com/u/0/116929729585101746795',
            'youtube_url' => 'https://www.youtube.com/channel/UChBCLCOY4GP3hGs2FBOME7A',
            'tumblr_url' => 'https://freebtcwebsite.tumblr.com',
            'vimeo_url' => 'https://vimeo.com/robattfield',
            'vkontakte_url' => 'https://vk.com/azh',
            'sinaweibo_url' => 'https://www.weibo.com/bitcoinbitcoin',
            'xing_url' => 'https://www.xing.com/profile/Jamie_Attfield',
            'user_id' => $adminUser->id
        ]);

        $socialLinks->save();
    }
}
