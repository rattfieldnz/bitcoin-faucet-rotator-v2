<?php


namespace App\Helpers\Functions;
use App\Helpers\Constants;
use App\Helpers\Social\Twitter;
use App\Models\Alert;

/**
 * Class Alerts
 *
 * A class to handle functionality specific to alerts.
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers\Functions
 */
class Alerts
{
    /**
     * Render a tweet for an alert.
     *
     * @param \App\Models\Alert $alert
     *
     * @return string
     */
    public static function renderTweet(Alert $alert)
    {
        if (empty($alert)) {
            return "";
        }

        $placeholders = [
            Constants::ALERT_TITLE_PLACEHOLDER => $alert->title,
            Constants::ALERT_URL_PLACEHOLDER => route('alerts.show', ['slug' => $alert->slug]),
            Constants::ALERT_SUMMARY_PLACEHOLDER => $alert->summary,
            Constants::ALERT_PUBLISHED_AT_PLACEHOLDER => $alert->publish_at
        ];

        $tweet = !empty($alert->twitter_message) ? strtr($alert->twitter_message, $placeholders) : "";
        return $tweet;
    }

    /**
     * Send a tweet for an alert.
     * 
     * @param \App\Models\Alert $alert
     * @return void
     */
    public static function sendTweet(Alert $alert){

        if (!empty($alert)) {
            $twitter = new Twitter(Users::adminUser());
            $tweet = self::renderTweet($alert);
            $twitter->sendTweet($tweet);
        }
    }
}