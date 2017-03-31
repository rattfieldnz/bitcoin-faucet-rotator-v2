<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateTwitterConfigRequest;
use App\Http\Requests\UpdateTwitterConfigRequest;
use App\Repositories\TwitterConfigRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TwitterConfigController extends AppBaseController
{
    /** @var  TwitterConfigRepository */
    private $twitterConfigRepository;

    public function __construct(TwitterConfigRepository $twitterConfigRepo)
    {
        $this->twitterConfigRepository = $twitterConfigRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the TwitterConfig.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.index', [], []);
        $this->twitterConfigRepository->pushCriteria(new RequestCriteria($request));
        $twitterConfigs = $this->twitterConfigRepository->all();
        $adminUser = Auth::user()->where('is_admin', true)->first();
        //dd($adminUser);

        if (count($twitterConfigs) == 0) {
            return view('twitter_config.create')->with('adminUser', $adminUser);
        }

        $twitterConfig = $this->twitterConfigRepository->first();

        return view('twitter_config.edit')
            ->with('twitterConfig', $twitterConfig)->with('adminUser', $adminUser);
    }

    /**
     * Show the form for creating a new TwitterConfig.
     *
     * @return Response
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.create', [], []);
        return view('twitter_config.create');
    }

    /**
     * Store a newly created TwitterConfig in storage.
     *
     * @param CreateTwitterConfigRequest $request
     *
     * @return Response
     */
    public function store(CreateTwitterConfigRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.store', [], []);
        $input = $request->all();

        $twitterConfig = $this->twitterConfigRepository->create($input);

        Flash::success('Twitter Config saved successfully.');

        return redirect(route('twitter-config.index'));
    }

    /**
     * Show the form for editing the specified TwitterConfig.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.edit', ['id' => $id], ['id' => $id]);
        $twitterConfig = $this->twitterConfigRepository->findWithoutFail($id);

        if (empty($twitterConfig)) {
            Flash::error('Twitter Config not found');

            return redirect(route('twitter-config.index'));
        }

        return view('twitter_config.edit')->with('twitterConfig', $twitterConfig);
    }

    /**
     * Update the specified TwitterConfig in storage.
     *
     * @param  int              $id
     * @param UpdateTwitterConfigRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTwitterConfigRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.update', ['id' => $id], ['id' => $id]);
        $twitterConfig = $this->twitterConfigRepository->findWithoutFail($id);

        if (empty($twitterConfig)) {
            Flash::error('Twitter Config not found');

            return redirect(route('twitter-config.index'));
        }

        $twitterConfig = $this->twitterConfigRepository->update($request->all(), $id);

        Flash::success('Twitter Config updated successfully.');

        return redirect(route('twitter-config.index'));
    }

    /**
     * Remove the specified TwitterConfig from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.destroy', ['id' => $id], ['id' => $id]);
        $twitterConfig = $this->twitterConfigRepository->findWithoutFail($id);

        if (empty($twitterConfig)) {
            Flash::error('Twitter Config not found');

            return redirect(route('twitterConfigs.index'));
        }

        $this->twitterConfigRepository->delete($id);

        Flash::success('Twitter Config deleted successfully.');

        return redirect(route('twitter-config.index'));
    }
}
