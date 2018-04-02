<?php

namespace App\Libraries\Google\Analytics;

use Analytics;
use App\Helpers\Functions\Dates;
use App\Helpers\Functions\Http;
use Google_Service_Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\Analytics\Period;
use Carbon\Carbon;

/**
 * Class GoogleAnalytics
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Libraries\Google\Analytics
 *
 * @see https://github.com/spatie/laravel-analytics/issues/148
 */
class GoogleAnalytics
{
    /**
    * @var $translateUrlPath Temporary bug fix - error is thrown when querying ga:pagePath%3D%3D
    * and the page path begins with '/translate'.
    */
    public static $translateUrlPath = "/translate";

    /**
     * Get list of countries and number of sessions for given number of days.
     *
     * @param int $numberOfDays
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countries(int $numberOfDays = 1) : Collection
    {
            $countries = Analytics::performQuery(
                Period::days($numberOfDays),
                'ga:sessions',
                ['dimensions' => 'ga:country', 'sort' => '-ga:sessions']
            );
            $data = $countries['rows'];
            return self::getCountryData($data);
    }

    /**
     * Retrieve countries and their amount of visitors between given dates.
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countriesBetweenTwoDates(string $fromDate, string $toDate): Collection
    {
        if ((empty($fromDate) || empty($toDate)) || ($fromDate == 'null' || $toDate == 'null')) {
            return Http::exceptionAsCollection("Both dates must not be empty.");
        }

        $startDateValue = Dates::createDateTime($fromDate);
        $endDateValue = Dates::createDateTime($toDate);

        if ($startDateValue == false || $endDateValue == false) {
            Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
        }

        if ($endDateValue->lessThan($startDateValue)) {
            return Http::exceptionAsCollection("End date must be greater than / after start date.");
        }

        if ($startDateValue->greaterThan($endDateValue)) {
            return Http::exceptionAsCollection("Start date must be less than / after start date.");
        }

        if (!empty($startDateValue) && !empty($endDateValue)) {
            $period = Period::create($startDateValue, $endDateValue);
            $countries = Analytics::performQuery(
                $period,
                'ga:sessions',
                ['dimensions' => 'ga:country', 'sort' => '-ga:sessions']
            );

            return self::getCountryData($countries['rows']);
        } else {
            return collect();
        }
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
        foreach ($array as $k => $v) {
            array_push($dataSets['labels'], $array[$k] ['browser']);
            array_push($dataSets['datasets']['data'], $array[$k] ['sessions']);
        }

        return $dataSets;
    }

    /**
     * Get Top x browsers overall.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int    $maxBrowsers
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public static function topBrowsersBetweenTwoDates(string $startDate, string $endDate, int $maxBrowsers = 10): Collection
    {
        if ((empty($startDate) || empty($endDate)) || ($startDate == 'null' || $endDate == 'null')) {
            return Http::exceptionAsCollection("Both dates must not be empty.");
        }

        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        if ($startDateValue == false || $endDateValue == false) {
            Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
        }

        if ($endDateValue->lessThan($startDateValue)) {
            return Http::exceptionAsCollection("End date must be greater than / after start date.");
        }

        if ($startDateValue->greaterThan($endDateValue)) {
            return Http::exceptionAsCollection("Start date must be less than / after start date.");
        }

        if (!empty($startDateValue) && !empty($endDateValue)) {
            $period = Period::create($startDateValue, $endDateValue);

            return Analytics::fetchTopBrowsers($period, $maxBrowsers);
        } else {
            return collect();
        }
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
     * @param string $startDate
     * @param string $endDate
     * @param int    $count
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public static function topPagesBetweenTwoDates(string $startDate, string $endDate, int $count = null)
    {

        if ((empty($startDate) || empty($endDate)) || ($startDate == 'null' || $endDate == 'null')) {
            return Http::exceptionAsCollection("Both dates must not be empty.");
        }

        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        if ($startDateValue == false || $endDateValue == false) {
            Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
        }

        if ($endDateValue->lessThan($startDateValue)) {
            return Http::exceptionAsCollection("End date must be greater than / after start date.");
        }

        if ($startDateValue->greaterThan($endDateValue)) {
            return Http::exceptionAsCollection("Start date must be less than / after start date.");
        }

        $period = Period::create($startDateValue, $endDateValue);
        $metrics = 'ga:visitors,ga:pageViews,ga:uniquePageviews,ga:avgSessionDuration,ga:avgTimeOnPage,ga:bounces';
        $dimensions =
            [
                'dimensions' => 'ga:pagePath,ga:pageTitle',
                'sort' => 'ga:visitors',
            ];

        if (!empty($count)) {
            $dimensions['max-results'] = $count;
        }

        $results = Analytics::performQuery($period, $metrics, $dimensions);

        if (!empty($results['rows'])) {
            $data = array_values($results['rows']);

            foreach ($data as $key => $value) {
                array_push($data[$key], self::getNoOfCountries($period, $data[$key][0]));
            }

            foreach ($data as $key => $value) {
                array_push($data[$key], $startDateValue);
                array_push($data[$key], $endDateValue);
            }

            return collect($data ?? [])->map(function (array $pageRow) {
                return [
                    'url' => [
                        'display' => $pageRow[0],
                        'original' => $pageRow[1]
                    ],
                    'uniqueVisitors' => [
                        'display' => number_format((int)$pageRow[2]),
                        'original' => (int)$pageRow[2]
                    ],
                    'pageViews' => [
                        'display' => number_format((int)$pageRow[3]),
                        'original' => (int)$pageRow[3]
                    ],
                    'uniquePageViews' => [
                        'display' => number_format((int)$pageRow[4]),
                        'original' => (int)$pageRow[4]
                    ],
                    'aveSessionDuration' => [
                        'display' => Dates::seconds2human(ceil(floatval($pageRow[5]))),
                        'original' => floatval($pageRow[5])
                    ],
                    'aveTimeOnPage' => [
                        'display' => Dates::seconds2human(ceil(floatval($pageRow[6]))),
                        'original' => floatval($pageRow[6])
                    ],
                    'noOfBounces' => [
                        'display' => number_format((int)$pageRow[7]),
                        'original' => (int)$pageRow[7]
                    ],
                    'noOfCountries' => (int)$pageRow[8], // noOfCountries
                    'start' => $pageRow[9]->toDateString() . ' ' . $pageRow[9]->toTimeString(), // start
                    'end' => $pageRow[10]->toDateString() . ' ' . $pageRow[10]->toTimeString() // end
                ];
            });
        } else {
            return collect();
        }
    }

    /**
     * Get total number of visits and page-views between given dates.
     *
     * @param          $startDate
     * @param          $endDate
     * @param int|null $count
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public static function visitsAndPageViews($startDate, $endDate, int $count = null): Collection
    {
        if ((empty($startDate) || empty($endDate)) || ($startDate == 'null' || $endDate == 'null')) {
            return Http::exceptionAsCollection("Both dates must not be empty.");
        }

        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        if ($startDateValue == false || $endDateValue == false) {
            Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
        }

        if ($endDateValue->lessThan($startDateValue)) {
            return Http::exceptionAsCollection("End date must be greater than / after start date.");
        }

        if ($startDateValue->greaterThan($endDateValue)) {
            return Http::exceptionAsCollection("Start date must be less than / after start date.");
        }

        $period = Period::create($startDateValue, $endDateValue);
        $metrics = 'ga:users,ga:pageviews';
        $dimensions =
            [
                'dimensions' => 'ga:date'
            ];

        if (!empty($count)) {
            $dimensions['max-results'] = $count;
        }

        $response = Analytics::performQuery(
            $period,
            $metrics,
            $dimensions
        );

        return collect($response['rows'] ?? [])->map(function (array $dateRow) {
            $date = Carbon::createFromFormat('Ymd', $dateRow[0]);
            return [
                'date' => $date->toDateString(),
                'visitors' => (int)$dateRow[1],
                'pageViews' => (int)$dateRow[2],
            ];
        });
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
     * @return int|Collection
     */
    public static function getNoOfCountries(Period $period, $urlPath) : int
    {
        if (!empty($period) && substr($urlPath, 0, strlen(self::$translateUrlPath)) != self::$translateUrlPath) {
            $results = Analytics::performQuery(
                $period,
                'ga:sessions',
                ['dimensions' => 'ga:country', 'filters' => 'ga:pagePath%3D%3D' . $urlPath]
            );

            return count($results['rows']);
        } else {
            return 0;
        }
    }

    /**
     * A function to format country-visitors data.
     *
     * @param $data
     *
     * @return \Illuminate\Support\Collection
     */
    private static function getCountryData(array $data): Collection
    {
        if (!empty($data)) {
            array_unshift($data, ['Country', 'Visitors']);
            $result = collect($data ?? [])->map(function (array $dataRow) {
                return [
                    $dataRow[0],
                    is_int($dataRow[1]) ? intval($dataRow[1]) : $dataRow[1],
                ];
            });
            return $result;
        } else {
            $data = collect();
            $data->push(['Country', 'Visitors'])
                ->push(['New Zealand', 0]); // Set default country and number of visitors.
            return $data;
        }
    }

    /**
     * Return a Google_service_Exception as a collection (good for API usage with Laravel framework).
     *
     * @param \Google_Service_Exception $e
     *
     * @see https://github.com/spatie/laravel-analytics
     * @see https://github.com/google/google-api-php-client/blob/master/src/Google/Service/Exception.php
     * @see https://developers.google.com/analytics/devguides/reporting/core/v3/errors
     * @see https://github.com/laravel/framework/blob/5.4/src/Illuminate/Support/Collection.php
     *
     * @return \Illuminate\Support\Collection
     */
    public static function googleException(Google_Service_Exception $e): Collection
    {
        $status = "error";

        $alternativeException = collect([
            'status' => $status,
            'code' => 500,
            'reason' => 'unknown',
            'message' => "There is an issue with your usage of Google Analytics API - this has been logged to your app and server logs."
        ]);

        Log::error($e->getMessage());

        if ($e instanceof \Google_Service_Exception && !empty($e)) {
            $errorException = json_decode($e->getMessage())->error;

            if (!empty($errorException) && !empty($errorException->errors[0])) {
                $reason = $errorException->errors[0]->reason;
                $message = $errorException->errors[0]->message;
                $code = $errorException->code;

                return collect(
                    [
                        'status' => $status,
                        'code' => $code,
                        'reason' => $reason,
                        'message' => $message . " This has been logged to your app logs."
                    ]
                );
            } else {
                return $alternativeException;
            }
        } else {
            return $alternativeException;
        }
    }
}
