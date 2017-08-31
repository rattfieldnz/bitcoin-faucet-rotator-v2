<?php

namespace App\Http\Controllers\API;

use App\Helpers\Constants;
use App\Helpers\Functions\Dates;
use App\Helpers\Functions\Http;
use App\Http\Controllers\AppBaseController;
use App\Libraries\DataTables\DataTables;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Google_Service_Exception;
use Yajra\DataTables\Utilities\Request;

/**
 * Class StatsAPIController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\API
 */
class StatsAPIController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('create_fresh_api_token:api');
    }

    /**
     * Get top x pages between date_from and date_to.
     *
     * @param \Yajra\DataTables\Utilities\Request $request
     * @param string                    $dateFrom
     * @param string                    $dateTo
     * @param int                       $quantity
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::topPagesBetweenTwoDates()
     *
     */
    public function getPagesVisited(Request $request, string $dateFrom, string $dateTo, int $quantity = 20)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);

        try {
            if (empty($fromDateInput) || empty($toDateInput)) {
                return Http::exceptionAsCollection("From and to dates cannot be empty.");
            } else if (Dates::createDateTime($fromDateInput, Constants::DATE_FORMAT_DMY) == false ||
                Dates::createDateTime($toDateInput, Constants::DATE_FORMAT_DMY) == false
            ) {
                return Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
            } else {
                $quantity = intval($quantity);

                $data = GoogleAnalytics::topPagesBetweenTwoDates($fromDateInput, $toDateInput, $quantity);

                return (new DataTables($request))->collection($data)->make(true);
            }
        } catch (Google_Service_Exception $e) {
            return GoogleAnalytics::googleException($e);
        } catch (\Exception $e) {
            return Http::exceptionAsCollection($e->getMessage());
        }
    }

    /**
     * Get total number of visitors and page views between given dates.
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param int    $quantity
     *
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::visitsAndPageViews()
     *
     * @return \Illuminate\Support\Collection
     */
    public function getVisitorsAndPageViews(string $dateFrom, string $dateTo, int $quantity = 20)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);

        if (empty($fromDateInput) || empty($toDateInput)) {
            return Http::exceptionAsCollection("From and to dates cannot be empty.");
        } else if (Dates::createDateTime($fromDateInput, Constants::DATE_FORMAT_DMY) == false ||
            Dates::createDateTime($toDateInput, Constants::DATE_FORMAT_DMY) == false
        ) {
            return Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
        }

        $quantity = intval($quantity);

        try {
            return GoogleAnalytics::visitsAndPageViews($fromDateInput, $toDateInput, $quantity);
        } catch (Google_Service_Exception $e) {
            return GoogleAnalytics::googleException($e);
        } catch (\Exception $e) {
            return Http::exceptionAsCollection($e->getMessage());
        }
    }

    /**
     * Get top x popular browsers (according to visitor views), between given dates.
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param int    $maxBrowserCount
     *
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::topBrowsersBetweenTwoDates()
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopBrowsersAndVisitors(string $dateFrom, string $dateTo, int $maxBrowserCount = 10)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);
        try {
            if (empty($fromDateInput) || empty($toDateInput)) {
                return Http::exceptionAsCollection("From and to dates cannot be empty.");
            } else if (Dates::createDateTime($fromDateInput, Constants::DATE_FORMAT_DMY) == false ||
                Dates::createDateTime($toDateInput, Constants::DATE_FORMAT_DMY) == false
            ) {
                return Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
            } else {
                $maxBrowserCount = intval($maxBrowserCount);

                return GoogleAnalytics::topBrowsersBetweenTwoDates($fromDateInput, $toDateInput, $maxBrowserCount);
            }
        } catch (Google_Service_Exception $e) {
            return GoogleAnalytics::googleException($e);
        } catch (\Exception $e) {
            return Http::exceptionAsCollection($e->getMessage());
        }
    }

    /**
     * Retrieve countries and their amount of visitors between given dates.
     *
     * @param string $dateFrom
     * @param string $dateTo
     *
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::countriesBetweenTwoDates()
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCountriesAndVisitors(string $dateFrom, string $dateTo)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);
        try {
            if (empty($fromDateInput) || empty($toDateInput)) {
                return Http::exceptionAsCollection("From and to dates cannot be empty.");
            } else if (Dates::createDateTime($fromDateInput, Constants::DATE_FORMAT_DMY) == false ||
                Dates::createDateTime($toDateInput, Constants::DATE_FORMAT_DMY) == false
            ) {
                return Http::exceptionAsCollection("Both dates must be valid - e.g.: 23/08/2017.");
            } else {
                return GoogleAnalytics::countriesBetweenTwoDates($fromDateInput, $toDateInput);
            }
        } catch (Google_Service_Exception $e) {
            return GoogleAnalytics::googleException($e);
        } catch (\Exception $e) {
            return Http::exceptionAsCollection($e->getMessage());
        }
    }
}
