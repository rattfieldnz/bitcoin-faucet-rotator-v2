<?php

namespace App\Listeners\Users;

use App\Helpers\Constants;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

/**
 * Class UpdateLastLoggedInAt
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Listeners\Users
 */
class UpdateLastLoggedInAt
{
    private $request;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (!empty($event->user)) {
            $user = $event->user;
            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $this->request->getClientIp();
            $user->save();

            activity(Constants::AUTH_LOGIN_LOG_NAME)
                ->causedBy($user)
                ->performedOn($user)
                ->withProperty('ip_address', $user->last_login_ip)
                ->log("User '" . $user->user_name . "' successfully logged in");
        }
    }
}
