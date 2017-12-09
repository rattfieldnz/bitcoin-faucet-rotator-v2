<?php

namespace App\Console\Commands;

use App\Helpers\Functions\Users;
use Illuminate\Console\Command;
use App\Helpers\Social\Twitter;

class SendRandomFaucetTweet extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'faucets:send-random-tweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to send a random faucet tweet to a designated Twitter account.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = Users::adminUser();
        $twitter = new Twitter($user);

        if (!empty($twitter) && !empty($user) && env('APP_ENV') == 'production') {
            $twitter->sendRandomFaucetTweet();
        }
    }
}
