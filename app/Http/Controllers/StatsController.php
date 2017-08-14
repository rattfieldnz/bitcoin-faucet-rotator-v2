<?php

namespace App\Http\Controllers;

use Analytics;
use App\Helpers\Functions\Dates;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Yajra\Datatables\Facades\Datatables;

class StatsController extends Controller
{
    private $data = [];
    public function index()
    {

        $this->data['browserjson'] = GoogleAnalytics::topbrowsers(7);

        $result = GoogleAnalytics::countries(1);
        $this->data['countries'] = $result;

        return view('stats.dashboard', $this->data);
    }
}
