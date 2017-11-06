<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAlertTypeRequest;
use App\Http\Requests\UpdateAlertTypeRequest;
use App\Repositories\AlertTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class AlertTypeController

 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class AlertTypeController extends AppBaseController
{
    /** @var  AlertTypeRepository */
    private $alertTypeRepository;

    public function __construct(AlertTypeRepository $alertTypeRepo)
    {
        $this->alertTypeRepository = $alertTypeRepo;
    }

    /**
     * Display a listing of the AlertType.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->alertTypeRepository->pushCriteria(new RequestCriteria($request));
        $alertTypes = $this->alertTypeRepository->all();

        return view('alert_types.index')
            ->with('alertTypes', $alertTypes);
    }

    /**
     * Show the form for creating a new AlertType.
     *
     * @return Response
     */
    public function create()
    {
        return view('alert_types.create');
    }

    /**
     * Store a newly created AlertType in storage.
     *
     * @param CreateAlertTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateAlertTypeRequest $request)
    {
        $input = $request->all();

        $alertType = $this->alertTypeRepository->create($input);

        Flash::success('Alert Type saved successfully.');

        return redirect(route('alertTypes.index'));
    }

    /**
     * Display the specified AlertType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $alertType = $this->alertTypeRepository->findWithoutFail($id);

        if (empty($alertType)) {
            Flash::error('Alert Type not found');

            return redirect(route('alertTypes.index'));
        }

        return view('alert_types.show')->with('alertType', $alertType);
    }
}
