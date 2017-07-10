<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 2/07/2017
 * Time: 19:56
 */

namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class LogEvent
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Events
 */
class LogEvent extends Event
{
    use SerializesModels;

    public $user, $subject, $description, $ipAddress;

    /**
     * LogEvent constructor.
     *
     * @param $user
     * @param $subject
     * @param $description
     * @param $ipAddress
     */
    public function __construct($user, $subject, $description, $ipAddress)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->description = $description;
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {

        return [];
    }
}
