<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Alerts;
use App\Helpers\Functions\Users;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreateAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Libraries\Seo\SeoConfig;
use App\Repositories\AlertRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
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
    private $alertFunctions;

    public function __construct(AlertRepository $alertRepo, Alerts $alertFunctions)
    {
        $this->alertRepository = $alertRepo;
        $this->alertFunctions = $alertFunctions;
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
        try {
            $this->alertRepository->pushCriteria(new RequestCriteria($request));
            $alerts = null;

            if (Auth::guest() || Auth::user()->hasRole('user') && !Auth::user()->isAnAdmin()) {
                $alerts = $this->alertRepository->all();
            } else {
                $alerts = $this->alertRepository->withTrashed()->get();
            }

            $seoConfig = new SeoConfig();
            $seoConfig->title = "List of Website Alerts (" . count($alerts) . ")";
            $seoConfig->description = "This page shows all alerts that are currently viewable to the public. There are a total of " .
                count($alerts) . " alerts in the faucet rotator.";
            $seoConfig->keywords = [
                "Website Alerts",
                "Alerts",
                "Website Messages",
                "Free Bitcoins",
                "Get Free Bitcoins",
                "Satoshis"
            ];
            $seoConfig->publishedTime = Carbon::now()->toW3cString();
            $seoConfig->modifiedTime = Carbon::now()->toW3cString();
            $seoConfig->authorName = Users::adminUser()->fullName();
            $seoConfig->currentUrl = route('alerts.index');
            $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
            $seoConfig->categoryDescription = "Website Alerts";
            WebsiteMeta::setCustomMeta($seoConfig);

            $disqusIdentifier = Users::adminUser()->user_name . '-' . Users::adminUser()->id . '-alerts-index';

            return view('alerts.index')
                ->with('alerts', $alerts)
                ->with('currentUrl', route('alerts.index'))
                ->with('disqusIdentifier', $disqusIdentifier);
        } catch (RepositoryException $e) {
            abort(500, $e->getMessage());
        }
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

        return view('alerts.create');
    }

    /**
     * Store a newly created Alert in storage.
     *
     * @param CreateAlertRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Response
     */
    public function store(CreateAlertRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $request->flash();

        $this->alertFunctions->createStoreAlert($request);

        Flash::success('Alert saved successfully.');

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
        $alert = $this->alertRepository->findByField('slug', $slug)->first();

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $disqusIdentifier = Users::adminUser()->user_name . '-' . Users::adminUser()->id . '-alerts-' . $alert->id;

        Alerts::setMeta($alert);

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

        $alert = $this->alertRepository->findByField('slug', $slug)->first();

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        return view('alerts.edit')->with('alert', $alert);
    }

    /**
     * Update the specified Alert in storage.
     *
     * @param $slug
     * @param UpdateAlertRequest $request
     *
     * @return \Response
     */
    public function update($slug, UpdateAlertRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findByField('slug', $slug)->first();

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $alert = $this->alertRepository->update($request->all(), $slug);

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
     * @param $slug
     *
     * @return \Response
     *
     */
    public function destroy($slug)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findByField('slug', $slug)->first();
        $logAlert = $this->alertRepository->findByField('slug', $slug)->first();

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('alerts.index'));
        }

        $this->alertRepository->deleteWhere(['slug' => $alert->slug]);

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
     * @param $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyPermanently($slug)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $alert = $this->alertRepository->findByField('slug', $slug, true)->first();
        $logAlert = $this->alertRepository->findByField('slug', $slug, true)->first();

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
     * @param  $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreDeleted($slug)
    {
        $alert = $this->alertRepository->findByField('slug', $slug, true)->first();
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
