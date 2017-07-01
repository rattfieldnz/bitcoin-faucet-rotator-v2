<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateAdBlockRequest;
use App\Http\Requests\UpdateAdBlockRequest;
use App\Models\User;
use App\Repositories\AdBlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
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
     * Display a listing of the AdBlock.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.index', [], []);

        $this->adBlockRepository->pushCriteria(new RequestCriteria($request));
        $adBlocks = $this->adBlockRepository->all();
        $adminUser = User::where('is_admin', true)->first();

        if (count($adBlocks) == 0) {
            return view('ad_block.create');
        }

        $adBlock = $this->adBlockRepository->first();
        return view('ad_block.edit')
            ->with('adBlock', $adBlock)
            ->with('adminUser', $adminUser);
    }

    /**
     * Show the form for creating a new AdBlock.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.create', [], []);
        return view('ad_block.create');
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

        return redirect(route('ad-block.index'));
    }

    /**
     * Show the form for editing the specified AdBlock.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.edit', ['id' => $id], ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            flash('Ad Block not found')->error();

            return redirect(route('ad-block.index'));
        }

        return view('ad_block.edit')->with('adBlock', $adBlock);
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

            return redirect(route('ad-block.index'));
        }

        $this->adBlockRepository->update($request->all(), $id);

        flash('Ad Block updated successfully.')->success();

        return redirect(route('ad-block.index'));
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
