<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

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
     * @see \App\Libraries\Google\Analytics\GoogleAnalytics::topPagesBetweenTwoDates()
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPagesVisited(string $dateFrom, string $dateTo, int $quantity = 20)
    {

        $fromDateInput = urldecode($dateFrom);
        $toDateInput = urldecode($dateTo);
        $quantity = intval($quantity);
        $data = GoogleAnalytics::topPagesBetweenTwoDates($fromDateInput, $toDateInput, $quantity);

        //$dataTables = Datatables::collection($data)->make(true);

        return $data;
    }
}
