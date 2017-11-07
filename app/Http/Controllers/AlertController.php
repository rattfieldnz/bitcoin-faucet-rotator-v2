<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Users;
use App\Http\Requests\CreateAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Repositories\AlertRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class AlertController
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class AlertController extends AppBaseController
{
    /** @var  AlertRepository */
    private $alertRepository;

    public function __construct(AlertRepository $alertRepo)
    {
        $this->alertRepository = $alertRepo;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the Alert.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Response
     */
    public function index(Request $request)
    {
        $this->alertRepository->pushCriteria(new RequestCriteria($request));
        $alerts = null;

        if (Auth::guest() || Auth::user()->hasRole('user') && !Auth::user()->isAnAdmin()) {
            $alerts = $this->alertRepository->all();
        } else {
            $alerts = $this->alertRepository->withTrashed()->get();
        }

        $disqusIdentifier = Users::adminUser()->user_name . '-' . Users::adminUser()->id . '-alerts-index';
        return view('alerts.index')
            ->with('alerts', $alerts)
            ->with('currentUrl', route('alerts.index'))
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Show the form for creating a new Alert.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        //dd(\App\Models\AlertIcon::all());

        return view('alerts.create');
    }

    /**
     * Store a newly created Alert in storage.
     *
     * @param CreateAlertRequest $request
     *
     * @return Response
     */
    public function store(CreateAlertRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $input = $request->all();

        $alert = $this->alertRepository->create($input);

        Flash::success('Alert saved successfully.');

        activity('alert_logs')
            ->performedOn($alert)
            ->causedBy(Auth::user())
            ->log("The alert ':subject.name' was added to the collection by :causer.user_name");

        return redirect(route('alerts.index'));
    }

    /**
     * Display the specified Alert.
     *
     * @param  $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Response
     */
    public function show($slug)
    {
        $alert = $this->alertRepository->findWithoutFail($slug);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $disqusIdentifier = Users::adminUser()->user_name . '-' . Users::adminUser()->id . '-alerts-' . $alert->id;

        return view('alerts.show')
            ->with('alert', $alert)
            ->with('currentUrl', route('alerts.show', ['slug' => $alert->slug]))
            ->with('disqusIdentifier', $disqusIdentifier);
    }

    /**
     * Show the form for editing the specified Alert.
     *
     * @param  $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Response
     */
    public function edit($slug)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findWithoutFail($slug);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        return view('alerts.edit')->with('alert', $alert);
    }

    /**
     * Update the specified Alert in storage.
     *
     * @param                    $id
     * @param UpdateAlertRequest $request
     *
     * @return \Response
     */
    public function update($id, UpdateAlertRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $alert = $this->alertRepository->update($request->all(), $id);

        Flash::success('Alert updated successfully.');

        activity('alert_logs')
            ->performedOn($alert)
            ->causedBy(Auth::user())
            ->log("The alert ':subject.name' was updated by :causer.user_name");

        return redirect(route('alerts.index'));
    }

    /**
     * Remove the specified Alert from storage.
     *
     * @param $id
     *
     * @return \Response
     *
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findWithoutFail($id);
        $logAlert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $this->alertRepository->delete($id);

        Flash::success('Alert deleted successfully.');

        activity('alert_logs')
            ->performedOn($logAlert)
            ->causedBy(Auth::user())
            ->log('The alert \':subject.name\' was archived/deleted by :causer.user_name');

        return redirect(route('alerts.index'));
    }

    /**
     * Destroy the specified alert permanently.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($id)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findByField('slug', $id, true)->first();
        $logAlert = $this->alertRepository->findByField('slug', $id, true)->first();

        if (empty($alert)) {
            flash('Alert not found')->error();

            return redirect(route('alerts.index'));
        }

        $alertTitle = $alert->title;

        Schema::disableForeignKeyConstraints();
        $alert->forceDelete();
        Schema::enableForeignKeyConstraints();

        flash('The alert \''. $alertTitle .'\' was permanently deleted!')->success();

        activity('alert_logs')
            ->performedOn($logAlert)
            ->causedBy(Auth::user())
            ->log('The alert \':subject.name\' was permanently deleted by :causer.user_name');

        return redirect(route('alerts.index'));
    }



    /**
     * Restore a soft-deleted alert.
     *
     * @param  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($id)
    {
        $alert = $this->alertRepository->findByField('slug', $id, true)->first();
        $logAlert = $alert;

        if (empty($alert)) {
            flash('Alert not found')->error();

            return redirect(route('alerts.index'));
        }

        if (!empty($faucet) && !$faucet->isDeleted()) {
            flash('The alert has already been restored or is still active.')->error();

            return redirect(route('alerts.index'));
        }

        $this->alertRepository->restoreDeleted($alert->slug);

        flash('The \''. $alert->name .'\' alert was successfully restored!')->success();

        activity('alert_logs')
            ->performedOn($logAlert)
            ->causedBy(Auth::user())
            ->log("The alert ':subject.name' was restored by :causer.user_name");

        return redirect(route('alerts.index'));
    }
}
