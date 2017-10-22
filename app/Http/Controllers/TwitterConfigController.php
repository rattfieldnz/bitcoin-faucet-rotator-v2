<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateTwitterConfigRequest;
use App\Http\Requests\UpdateTwitterConfigRequest;
use App\Repositories\TwitterConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TwitterConfigController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class TwitterConfigController extends AppBaseController
{
    private $twitterConfigRepository;

    /**
     * TwitterConfigController constructor.
     *
     * @param \App\Repositories\TwitterConfigRepository $twitterConfigRepo
     */
    public function __construct(TwitterConfigRepository $twitterConfigRepo)
    {
        $this->twitterConfigRepository = $twitterConfigRepo;
        $this->middleware('auth');
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

        $this->twitterConfigRepository->create($input);

        flash('Twitter Config saved successfully.')->success();

        return redirect(route('settings') . "#twitter-config");
    }

    /**
     * Update the specified TwitterConfig in storage.
     *
     * @param int                        $id
     * @param UpdateTwitterConfigRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTwitterConfigRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.update', ['id' => $id], ['id' => $id]);
        $twitterConfig = $this->twitterConfigRepository->findWithoutFail($id);

        if (empty($twitterConfig)) {
            flash('Twitter Config not found.')->error();

            return redirect(route('settings') . "#twitter-config");
        }

        $this->twitterConfigRepository->update($request->all(), $id);

        flash('Twitter Config updated successfully.')->success();

        return redirect(route('settings') . "#twitter-config");
    }

    /**
     * Remove the specified TwitterConfig from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'twitter-config.destroy', ['id' => $id], ['id' => $id]);
        $twitterConfig = $this->twitterConfigRepository->findWithoutFail($id);

        if (empty($twitterConfig)) {
            flash('Twitter Config not found.')->error();

            return redirect(route('twitterConfigs.index'));
        }

        $this->twitterConfigRepository->delete($id);

        flash('Twitter Config deleted successfully')->success();

        return redirect(route('twitter-config.index'));
    }
}
