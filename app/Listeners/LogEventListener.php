<?php

namespace App\Listeners;

use App\Events\LogEvent;

/**
 * Class LogEventListener
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Listeners
 */
class LogEventListener
{

    /**
     * LogEventListener constructor.
     */
    public function __construct()
    {
        ;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LogEvent $event
     * @return void
     */
    public function handle(LogEvent $event)
    {

        activity()
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->withProperty('ip_address', $event->ipAddress)
            ->log($event->description);
    }
}
