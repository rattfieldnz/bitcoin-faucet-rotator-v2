<?php

namespace App\Listeners\Users;

use App\Helpers\Constants;
use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

/**
 * Class UpdateLastLoggedOutAt
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Listeners\Users
 */
class UpdateLastLoggedOutAt
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
     * @param  Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if (!empty($event->user)) {
            $user = $event->user;
            $user->last_logout_at = Carbon::now();
            $user->save();

            activity(Constants::AUTH_LOGOUT_LOG_NAME)
                ->causedBy($user)
                ->performedOn($user)
                ->withProperty('ip_address', $this->request->getClientIp())
                ->log("User '" . $user->user_name . "' successfully logged out");
        }
    }
}
