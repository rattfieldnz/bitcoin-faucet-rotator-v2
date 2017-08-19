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
 *
 * @see https://github.com/spatie/laravel-analytics/issues/148
 */
class GoogleAnalytics
{

    /**
     * Get list of countries and number of sessions for given number of days.
     *
     * @param int $numberOfDays
     *
     * @return \Illuminate\Support\Collection
     */
    public static function countries(int $numberOfDays = 1) : Collection
    {
        try {
            $countries = Analytics::performQuery(
                Period::days($numberOfDays),
                'ga:sessions',
                ['dimensions' => 'ga:country', 'sort' => '-ga:sessions']
            );
            $data = $countries['rows'];
            return self::getCountryData($data);
        } catch(\Google_Service_Exception $e){
            return self::googleException();
        }
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
        $startDateValue = Dates::createDateTime($fromDate);
        $endDateValue = Dates::createDateTime($toDate);

        try {
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
        } catch(\Google_Service_Exception $e){
            return self::googleException();
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
        try {
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
        } catch(\Google_Service_Exception $e){
            return self::googleException();
        }
    }

    /**
     * Get Top x browsers overall.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int    $maxBrowsers
     *
     * @return \Illuminate\Support\Collection
     */
    public static function topBrowsersBetweenTwoDates(string $startDate, string $endDate, int $maxBrowsers = 10): Collection
    {
        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        try {
            if (!empty($startDateValue) && !empty($endDateValue)) {
                $period = Period::create($startDateValue, $endDateValue);

                return Analytics::fetchTopBrowsers($period, $maxBrowsers);
            } else {
                return collect();
            }
        } catch(\Google_Service_Exception $e){
            return self::googleException();
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
     * @param     $startDate
     * @param     $endDate
     * @param int $count
     *
     * @return \Google_Service_Exception|\Illuminate\Support\Collection
     */
    public static function topPagesBetweenTwoDates(string $startDate, string $endDate, int $count = null)
    {
        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        try {

            if (!empty($startDateValue) && !empty($endDateValue)) {
                if ($endDateValue->lessThan($startDateValue)) {
                    $tmpStartDateValue = $endDateValue;
                    $tmpEndDateValue = $startDateValue;

                    $endDateValue = $tmpStartDateValue;
                    $startDateValue = $tmpEndDateValue;
                }

                if ($startDateValue->greaterThan($endDateValue)) {
                    $tmpEndDateValue = $startDateValue;
                    $tmpStartDateValue = $endDateValue;

                    $endDateValue = $tmpEndDateValue;
                    $startDateValue = $tmpStartDateValue;
                }

                $period = Period::create($startDateValue, $endDateValue);
                $metrics = 'ga:visitors,ga:pageViews,ga:uniquePageviews,ga:avgSessionDuration,ga:avgTimeOnPage,ga:bounces';
                $dimensions =
                    [
                        'dimensions' => 'ga:pagePath,ga:pageTitle',
                        'sort' => 'ga:visitors,ga:pageViews,ga:uniquePageviews,ga:avgSessionDuration,ga:avgTimeOnPage,ga:bounces',
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

                    //$date = Carbon::now()->toTimeString()

                    return collect($data ?? [])->map(function (array $pageRow) {
                        return [
                            'url' => $pageRow[0], // url
                            'pageTitle' => $pageRow[1], // pageTitle
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
            } else {
                return collect();
            }
        } catch(\Google_Service_Exception $e){
            return new \Google_Service_Exception("Google API limit Exceeded!", 500);
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
     */
    public static function visitsAndPageViews($startDate, $endDate, int $count = null): Collection
    {
        $startDateValue = Dates::createDateTime($startDate);
        $endDateValue = Dates::createDateTime($endDate);

        try {

            if ($endDateValue->lessThan($startDateValue)) {
                $tmpStartDateValue = $endDateValue;
                $tmpEndDateValue = $startDateValue;

                $endDateValue = $tmpStartDateValue;
                $startDateValue = $tmpEndDateValue;
            }

            if ($startDateValue->greaterThan($endDateValue)) {
                $tmpEndDateValue = $startDateValue;
                $tmpStartDateValue = $endDateValue;

                $endDateValue = $tmpEndDateValue;
                $startDateValue = $tmpStartDateValue;
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
        } catch(\Google_Service_Exception $e){
            return self::googleException();
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
     * @return int|Collection
     */
    public static function getNoOfCountries(Period $period, $urlPath) : int
    {
        try {
            if (!empty($period)) {
                $results = Analytics::performQuery(
                    $period,
                    'ga:sessions',
                    ['dimensions' => 'ga:country', 'filters' => 'ga:pagePath%3D%3D' . $urlPath]
                );

                return count($results['rows']);
            } else {
                return 0;
            }
        }  catch(\Google_Service_Exception $e){
            return self::googleException();
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
        try {
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
        } catch(\Google_Service_Exception $e){
            return self::googleException();
        }
    }

    public static function googleException(string $status = 'error', string $message = 'Google API limit Exceeded!'): Collection{
        return collect(
            [
                'status' => $status,
                'code' => 500,
                'message' => $message
            ]
        );
    }
}
