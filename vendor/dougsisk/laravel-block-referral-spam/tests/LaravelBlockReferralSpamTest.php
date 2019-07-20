<?php

class LaravelBlockReferralSpamTest extends Orchestra\Testbench\BrowserKit\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.referral_spam_list_location', dirname(__FILE__) . '/../vendor/matomo/referrer-spam-blacklist/spammers.txt');

        $app['router']->get('hello', function () {
            return 'hello world';
        });

        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('DougSisk\BlockReferralSpam\Middleware\BlockReferralSpam');
    }

    public function testValidRequest()
    {
        $this->call('GET', 'hello', [], [], [], [
            'HTTP_REFERER' => "http://www.google.com"
        ]);

        $this->assertResponseOk();
    }

    public function testInvalidRequest()
    {
        $this->call('GET', 'hello', [], [], [], [
            'HTTP_REFERER' => "http://allknow.info"
        ]);

        $this->assertResponseStatus(401);
    }

    public function testInvalidSubdomainRequest()
    {
        $this->call('GET', 'hello', [], [], [], [
            'HTTP_REFERER' => "http://test.subdomain.event-tracking.com"
        ]);

        $this->assertResponseStatus(401);
    }
}
