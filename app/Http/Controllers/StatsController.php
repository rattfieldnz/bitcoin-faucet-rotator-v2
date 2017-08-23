<?php

namespace App\Http\Controllers;

use Analytics;
use App\Helpers\Constants;
use App\Helpers\Functions\Dates;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class StatsController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //dd(Dates::createDateTime('20-21-2017',Constants::DATE_FORMAT_DMY));
        return view('stats.dashboard');
    }
}
