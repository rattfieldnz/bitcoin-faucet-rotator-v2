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

        $this->data['browserjson'] = GoogleAnalytics::topbrowsers(1);

        $result = GoogleAnalytics::countries(1);
        $this->data['countries'] = $result;

        //$dataTablesData = GoogleAnalytics::topPagesBetweenTwoDates('12-08-2017', '12-08-2017');
        //$this->data['dataTables'] = Datatables::collection($dataTablesData)->make(true);

        //dd(json_encode($this->data['dataTables']));

        return view('stats.dashboard', $this->data);
    }
}
