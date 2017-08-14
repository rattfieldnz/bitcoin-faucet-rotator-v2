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
        $result = GoogleAnalytics::countries(7);
        $this->data['countries'] = $result;

        return view('stats.dashboard', $this->data);
    }
}
