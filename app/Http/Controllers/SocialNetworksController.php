<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Users;
use App\Http\Requests\CreateSocialNetworksRequest;
use App\Http\Requests\UpdateSocialNetworksRequest;
use App\Repositories\SocialNetworksRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class SocialNetworksController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class SocialNetworksController extends AppBaseController
{
    /** @var  SocialNetworksRepository */
    private $socialNetworksRepository;

    public function __construct(SocialNetworksRepository $socialNetworksRepo)
    {
        $this->socialNetworksRepository = $socialNetworksRepo;
        $this->middleware('auth');
    }

    /**
     * Store a newly created SocialNetworks in storage.
     *
     * @param CreateSocialNetworksRequest $request
     *
     * @return Response
     */
    public function store(CreateSocialNetworksRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $input = $request->all();
        $input['user_id'] = Users::adminUser()->id;

        $socialNetworks = $this->socialNetworksRepository->create($input);

        Flash::success('Social Networks saved successfully.');

        return redirect(route('settings') . "#social-links");
    }

    /**
     * Update the specified SocialNetworks in storage.
     *
     * @param  int              $id
     * @param UpdateSocialNetworksRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSocialNetworksRequest $request)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $socialNetworks = $this->socialNetworksRepository->findWithoutFail($id);

        if (empty($socialNetworks)) {
            Flash::error('Social Networks not found');

            return redirect(route('settings') . "#social-links");
        }

        $input = $request->all();
        //$input['user_id'] = Users::adminUser()->id;

        $socialNetworks = $this->socialNetworksRepository->update($input, $id);

        Flash::success('Social Networks updated successfully.');

        return redirect(route('settings') . "#social-links");
    }

    /**
     * Remove the specified SocialNetworks from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $socialNetworks = $this->socialNetworksRepository->findWithoutFail($id);

        if (empty($socialNetworks)) {
            Flash::error('Social Networks not found');

            return redirect(route('settings') . "#social-links");
        }

        $this->socialNetworksRepository->delete($id);

        Flash::success('Social Networks deleted successfully.');

        return redirect(route('settings') . "#social-links");
    }
}
