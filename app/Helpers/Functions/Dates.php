<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 5/08/2017
 * Time: 16:25
 */

namespace App\Helpers\Functions;

use App\Helpers\Constants;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Log;

/**
 * Class Dates
 *
 * A class to handle creation and validation of dates and times.
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers\Functions
 */
class Dates
{
    /**
     * Check a date is in a valid format (e.g. 05-08-2017 => d/m/Y).
     *
     * @param        $dateString
     * @param string $dateFormat
     *
     * @return bool
     */
    public static function validDate($dateString, $dateFormat = Constants::DATE_FORMAT_DMY)
    {
        try {
            return Carbon::createFromFormat($dateFormat, $dateString) !== false ? true : false;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * Check a time is in a valid format (e.g. 18:53:00 => H:i:s).
     *
     * @param        $timeString
     * @param string $timeFormat
     *
     * @return bool
     */
    public static function validTime($timeString, $timeFormat = Constants::TIME_FORMAT_HIS)
    {
        try {
            return Carbon::createFromFormat($timeFormat, $timeString) !== false ? true : false;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * Check a full date, including time, is in a valid format (e.g. '05-08-2017 18:53:00' => 'd-m-Y H:i:s').
     *
     * @param        $dateTimeString
     * @param string $dateTimeFormat
     *
     * @return bool
     */
    public static function validDateTime($dateTimeString, $dateTimeFormat = Constants::DATETIME_FORMAT_DMY_HIS)
    {
        try {
            return Carbon::createFromFormat($dateTimeFormat, $dateTimeString) !== false ? true : false;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * Create a date from format.
     *
     * @param        $dateString
     * @param string $dateFormat
     *
     * @return \Carbon\Carbon
     */
    public static function createDate($dateString, $dateFormat = Constants::DATE_FORMAT_DMY) : Carbon
    {

        try {
            $dateValue = new Carbon($dateString);
            $date = Carbon::createFromFormat($dateFormat, $dateValue->format($dateFormat));
            return $date !== false ? $date : null;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * Create a time from format.
     *
     * @param        $timeString
     * @param string $timeFormat
     *
     * @return \Carbon\Carbon
     */
    public static function createTime($timeString, $timeFormat = Constants::TIME_FORMAT_HIS) : Carbon
    {

        try {
            $timeValue = new Carbon($timeString);
            $time = Carbon::createFromFormat($timeFormat, $timeValue->format($timeFormat));
            return $time !== false ? $time : null;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * Create a full date, including time, from format.
     * @param        $dateTimeString
     * @param string $dateTimeFormat
     *
     * @return \Carbon\Carbon|bool
     */
    public static function createDateTime($dateTimeString, $dateTimeFormat = Constants::DATETIME_FORMAT_DMY_HIS)
    {

        try {
            $dateTimeValue = new Carbon($dateTimeString);
            $dateTime = Carbon::createFromFormat($dateTimeFormat, $dateTimeValue->format($dateTimeFormat));
            return $dateTime != false ? $dateTime : false;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * A function to convert integer value into time format.
     *
     * @param int $seconds
     * @see https://hotexamples.com/examples/-/str/plural/php-str-plural-method-examples.html#0xfd94e5b6f9adef5dabe503c775a6487431286414882b2c35ff1b40bc398ace51-58,,93,
     *
     * @return string
     */
    public static function seconds2human(int $seconds): string
    {
        if ($seconds == 0) {
            return $seconds . ' seconds';
        }
        list($years, $months, $days, $hours, $minutes, $seconds) = explode(":", gmdate("Y:n:j:G:i:s", $seconds));
        $years -= 1970;
        $months--;
        $days--;
        $parts = [];
        if ($years > 0) {
            $parts[] = $years . " " . str::plural("year", $years);
        }
        if ($months > 0) {
            $parts[] = $months . " " . str::plural("month", $months);
        }
        if ($days > 0) {
            $parts[] = $days . " " . str::plural("day", $days);
        }
        if ($hours > 0) {
            $parts[] = $hours . " " . str::plural("hour", $hours);
        }
        if ($minutes > 0) {
            $parts[] = sprintf("%d", $minutes) . " " . str::plural("minute", $minutes);
        }
        if ($seconds > 0) {
            $parts[] = sprintf("%d", $seconds) . " " . str::plural("second", $seconds);
        }
        return implode(", ", $parts);
    }
}
