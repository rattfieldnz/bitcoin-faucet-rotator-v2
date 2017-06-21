<?php

use App\Models\TwitterConfig;
use App\Models\User;
use Illuminate\Database\Seeder;

class TwitterConfigTableSeeder extends Seeder
{
    public function run()
    {
        $keys = [];
        $user = User::find(1);
        $twitterConfig = new TwitterConfig();

        $keys['consumer_key'] = Purifier::clean(env('CONSUMER_KEY'), 'generalFields');
        $keys['consumer_key_secret'] = Purifier::clean(env('CONSUMER_KEY_SECRET'), 'generalFields');
        $keys['access_token'] = Purifier::clean(env('ACCESS_TOKEN'), 'generalFields');
        $keys['access_token_secret'] = Purifier::clean(env('ACCESS_TOKEN_SECRET'), 'generalFields');
        $keys['user_id'] = $user->id;

        $twitterConfig->fill($keys);
        $twitterConfig->save();
    }
}
