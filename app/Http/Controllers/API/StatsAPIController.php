<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

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
        //$this->middleware('auth');
    }

    /**
     * Get top x pages between date_from and date_to.
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param int    $quantity
     *
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::topPagesBetweenTwoDates()
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPagesVisited(string $dateFrom, string $dateTo, int $quantity = 20)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);
        $quantity = intval($quantity);
        $data = GoogleAnalytics::topPagesBetweenTwoDates($fromDateInput, $toDateInput, $quantity);

        return Datatables::collection($data)->make(true);
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
        $quantity = intval($quantity);

        return GoogleAnalytics::visitsAndPageViews($fromDateInput, $toDateInput, $quantity);
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
        $maxBrowserCount = intval($maxBrowserCount);

        return GoogleAnalytics::topBrowsersBetweenTwoDates($fromDateInput, $toDateInput, $maxBrowserCount);
    }

    public function getCountriesAndVisitors(string $dateFrom, string $dateTo)
    {
        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);

        return GoogleAnalytics::countriesBetweenTwoDates($fromDateInput, $toDateInput);
    }
}
