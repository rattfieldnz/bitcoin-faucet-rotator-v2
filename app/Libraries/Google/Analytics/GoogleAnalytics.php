<?php

namespace App\Libraries\Google\Analytics;

use Analytics;
use App\Helpers\Functions\Dates;
use Illuminate\Support\Collection;
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

    /**
     * Get list of countries and number of sessions for given number of days.
     *
     * @param int $numberOfDays
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countries(int $numberOfDays = 1) : Collection {
        $country = Analytics::performQuery(Period::days($numberOfDays),'ga:sessions',  ['dimensions'=>'ga:country','sort'=>'-ga:sessions']);
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

    /**
     * Get JSON-formatted string data of top browsers and quantity of visitors for
     * a given number of days.
     *
     * @param int $numberOfDays
     *
     * @return string
     */
    public static function topbrowsers(int $numberOfDays = 1)
    {
        $analyticsData = Analytics::fetchTopBrowsers(Period::days($numberOfDays));
        $array = $analyticsData->toArray();
        $dataSets = [];
        $dataSets['labels'] = [];
        $dataSets['datasets'] = [];
        $dataSets['datasets']['data'] = [];
        foreach ($array as $k=>$v)
        {
            array_push($dataSets['labels'], $array[$k] ['browser']);
            array_push($dataSets['datasets']['data'], $array[$k] ['sessions']);
        }

        return $dataSets;
    }

    /**
     * Get top x pages between date_from and date_to.
     *
     * Date can be in 'd/m/y H', 'd/m/Y H:i', 'd/m/Y H:i:s', 'd/m/Y'.
     *
     * Stats for each page in the collection include:
     *
     * - Page URL path (excluding root domain)
     * - Page title
     * - Total page views
     * - Total unique page views
     * - Average session duration (in seconds)
     * - Average amount of time on the page (in seconds)
     * - No. of users who left after 1st page view (bounces)
     * - No. of countries which registered total visits.
     * - Start date
     * - End date
     *
     * @param     $startDate
     * @param     $endDate
     * @param int $count
     *
     * @return \Illuminate\Support\Collection
     */
    public static function topPagesBetweenTwoDates($startDate, $endDate, $count = 20){
        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        if(!empty($startDateValue) && !empty($endDateValue)){

            if($endDateValue->lessThan($startDateValue)){

                $tmpStartDateValue = $endDateValue;
                $tmpEndDateValue = $startDateValue;

                $endDateValue = $tmpStartDateValue;
                $startDateValue = $tmpEndDateValue;
            }

            if($startDateValue->greaterThan($endDateValue)){

                $tmpEndDateValue = $startDateValue;
                $tmpStartDateValue = $endDateValue;

                $endDateValue = $tmpEndDateValue;
                $startDateValue = $tmpStartDateValue;

            }

            $period = Period::create($startDateValue, $endDateValue);
            $metrics = 'ga:pageviews,ga:uniquePageviews,ga:avgSessionDuration,ga:avgTimeOnPage,ga:bounces';
            $dimensions =
                [
                    'dimensions' => 'ga:pagePath,ga:pageTitle',
                    'sort' => '-ga:pageviews,-ga:uniquePageviews,-ga:avgSessionDuration,-ga:avgTimeOnPage,-ga:bounces',
                    'max-results' => $count,
                ];

            $results = Analytics::performQuery($period,$metrics,$dimensions);
            $data = array_values($results['rows']);

            foreach($data as $key => $value){
                array_push($data[$key], self::getNoOfCountries($period, $data[$key][0]));
            }

            foreach($data as $key => $value){
                array_push($data[$key], $startDateValue);
                array_push($data[$key], $endDateValue);
            }

            return collect($data ?? [])->map(function (array $pageRow) {
                return [
                    'url' => $pageRow[0],
                    'pageTitle' => $pageRow[1],
                    'pageViews' => (int) $pageRow[2],
                    'uniquePageViews' => (int) $pageRow[3],
                    'aveSessionDuration' => floatval($pageRow[4]),
                    'aveTimeOnPage' => floatval($pageRow[5]),
                    'noOfBounces' => (int) $pageRow[6],
                    'noOfCountries' => (int) $pageRow[7],
                    'start' => $pageRow[8],
                    'end' => $pageRow[9]
                ];
            });
        } else {
            return collect();
        }
    }

    /**
     * Get number of countries which have visited a URL
     * for a specific period.
     *
     * A period can be created with two dates.
     * @see \Spatie\Analytics\Period
     * @see https://github.com/spatie/laravel-analytics
     *
     * @param \Spatie\Analytics\Period $period
     * @param                          $urlPath
     *
     * @return int
     */
    public static function getNoOfCountries(Period $period, $urlPath) : int{
        if(!empty($period)){
            $results = Analytics::performQuery(
                $period,
                'ga:sessions',
                ['dimensions'=>'ga:country','filters' => 'ga:pagePath%3D%3D' . $urlPath]);

            return count($results['rows']);
        } else {
            return 0;
        }
    }
}