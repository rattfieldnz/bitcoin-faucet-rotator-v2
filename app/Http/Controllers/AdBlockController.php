<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateAdBlockRequest;
use App\Http\Requests\UpdateAdBlockRequest;
use App\Models\User;
use App\Repositories\AdBlockRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AdBlockController extends AppBaseController
{
    /** @var  AdBlockRepository */
    private $adBlockRepository;

    public function __construct(AdBlockRepository $adBlockRepo)
    {
        $this->adBlockRepository = $adBlockRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the AdBlock.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.index');

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
     * @return Response
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.create');
        return view('ad_block.create');
    }

    /**
     * Store a newly created AdBlock in storage.
     *
     * @param CreateAdBlockRequest $request
     *
     * @return Response
     */
    public function store(CreateAdBlockRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.store');
        $input = $request->all();

        $adBlock = $this->adBlockRepository->create($input);

        Flash::success('Ad Block saved successfully.');

        return redirect(route('ad-block.index'));
    }

    /**
     * Show the form for editing the specified AdBlock.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.edit', ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-block.index'));
        }

        return view('ad_block.edit')->with('adBlock', $adBlock);
    }

    /**
     * Update the specified AdBlock in storage.
     *
     * @param  int              $id
     * @param UpdateAdBlockRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdBlockRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'ad-block.update', ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-block.index'));
        }

        $adBlock = $this->adBlockRepository->update($request->all(), $id);

        Flash::success('Ad Block updated successfully.');

        return redirect(route('ad-block.index'));
    }

    /**
     * Remove the specified AdBlock from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {

        Functions::userCanAccessArea(Auth::user(), 'ad-block.destroy', ['id' => $id]);
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-block.index'));
        }

        $this->adBlockRepository->delete($id);

        Flash::success('Ad Block deleted successfully.');

        return redirect(route('ad-block.index'));
    }
}
