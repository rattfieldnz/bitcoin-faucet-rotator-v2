<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 19/08/2017
 * Time: 18:35
 */

namespace App\Libraries\DataTables;

use Google_Service_Exception;
use Yajra\DataTables\CollectionDataTable;
use Illuminate\Support\Collection;
use Yajra\Datatables\Utilities\Request;

class DataTables
{
    /**
     * Datatables request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * Datatables constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Datatables using Collection.
     *
     * @param Collection|mixed $collection
     *
     * @return \Yajra\Datatables\CollectionDataTable
     * @throws Google_Service_Exception
     */
    public function collection($collection)
    {
        if ($collection instanceof Google_Service_Exception) {
            throw new Google_Service_Exception("Google API limit Exceeded!", 500);
        }
        if (is_array($collection)) {
            $collection = new Collection($collection);
        }

        return new CollectionDataTable($collection, $this->request);
    }
}
