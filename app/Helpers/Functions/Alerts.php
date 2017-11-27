<?php


namespace App\Helpers\Functions;
use App\Helpers\Constants;
use App\Helpers\Social\Twitter;
use App\Http\Requests\CreateAlertRequest;
use App\Models\Alert;
use App\Repositories\AlertRepository;
use Illuminate\Support\Facades\Auth;

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
    private $alertRepository;

    /**
     * Alerts constructor.
     *
     * @param \App\Repositories\AlertRepository $alertRepository
     */
    public function __construct(AlertRepository $alertRepository)
    {
        $this->alertRepository = $alertRepository;
    }

    public function createStoreAlert(CreateAlertRequest $request){

        $alert = $this->alertRepository->create($request->all());

        if ($request->get('send_tweet') == 1 && env('APP_ENV') == 'production') {
            $twitter = new Twitter(Users::adminUser());
            $tweet = self::renderTweet($alert);
            $twitter->sendTweet($tweet);
        }

        activity(self::logName())
            ->performedOn($alert)
            ->causedBy(Auth::user())
            ->log("The alert ':subject.title' was added to the collection by :causer.user_name");
    }

    /**
     * @return string
     */
    public static  function logName(): string{
        return Constants::ALERT_LOG_NAME;
    }

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
            Constants::ALERT_PUBLISHED_AT_PLACEHOLDER => $alert->created_at
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