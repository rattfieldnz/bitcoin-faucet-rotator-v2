<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 3/08/2017
 * Time: 19:27
 */

namespace App\Libraries\Google\Analytics;

use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

/**
 * Class GoogleAnalytics
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Libraries\Google\Analytics
 * @see https://github.com/spatie/laravel-analytics/issues/148
 */
class GoogleAnalytics{

    static function country() {
    $country = Analytics::performQuery(Period::days(14),'ga:sessions',  ['dimensions'=>'ga:country','sort'=>'-ga:sessions']);
    $result= collect($country['rows'] ?? [])->map(function (array $dateRow) {
        return [
            'country' =>  $dateRow[0],
            'sessions' => (int) $dateRow[1],
        ];
    });
    /* $data['country'] = $result->pluck('country'); */
    /* $data['country_sessions'] = $result->pluck('sessions'); */
    return $result;
}

    static function topbrowsers()
    {
        $analyticsData = Analytics::fetchTopBrowsers(Period::days(14));
        $array = $analyticsData->toArray();
        foreach ($array as $k=>$v)
        {
            $array[$k] ['label'] = $array[$k] ['browser'];
            unset($array[$k]['browser']);
            $array[$k] ['value'] = $array[$k] ['sessions'];
            unset($array[$k]['sessions']);
            $array[$k]['color'] = $array[$k]['highlight'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
        return json_encode($array);
    }
}