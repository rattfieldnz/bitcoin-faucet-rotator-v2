<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateAdBlockRequest;
use App\Http\Requests\UpdateAdBlockRequest;
use App\Models\User;
use App\Repositories\AdBlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class AdBlockController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class AdBlockController extends AppBaseController
{
    private $adBlockRepository;

    /**
     * AdBlockController constructor.
     *
     * @param \App\Repositories\AdBlockRepository $adBlockRepo
     */
    public function __construct(AdBlockRepository $adBlockRepo)
    {
        $this->adBlockRepository = $adBlockRepo;
        $this->middleware('auth');
    }

    /**
     * Store a newly created AdBlock in storage.
     *
     * @param  CreateAdBlockRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateAdBlockRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.store', [], []);
        $input = $request->all();

        $this->adBlockRepository->create($input);

        flash('Ad Block saved successfully.')->success();

        return redirect(route('settings') . "#ad-block");
    }

    /**
     * Update the specified AdBlock in storage.
     *
     * @param  int                  $id
     * @param  UpdateAdBlockRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateAdBlockRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.update', ['id' => $id], ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            flash('Ad Block not found')->error();

            return redirect(route('settings') . "#ad-block");
        }

        $this->adBlockRepository->update($request->all(), $id);

        flash('Ad Block updated successfully.')->success();

        return redirect(route('settings') . "#ad-block");
    }

    /**
     * Remove the specified AdBlock from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.destroy', ['id' => $id], ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            flash('Ad Block not found')->error();

            return redirect(route('ad-block.index'));
        }

        $this->adBlockRepository->delete($id);

        flash('Ad Block deleted successfully.')->success();

        return redirect(route('ad-block.index'));
    }
}
