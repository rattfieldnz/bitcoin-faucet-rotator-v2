<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdBlockRequest;
use App\Http\Requests\UpdateAdBlockRequest;
use App\Repositories\AdBlockRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
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
        $this->adBlockRepository->pushCriteria(new RequestCriteria($request));
        $adBlocks = $this->adBlockRepository->all();

        return view('ad_blocks.index')
            ->with('adBlocks', $adBlocks);
    }

    /**
     * Show the form for creating a new AdBlock.
     *
     * @return Response
     */
    public function create()
    {
        return view('ad_blocks.create');
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
        $input = $request->all();

        $adBlock = $this->adBlockRepository->create($input);

        Flash::success('Ad Block saved successfully.');

        return redirect(route('ad-blocks.index'));
    }

    /**
     * Display the specified AdBlock.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-blocks.index'));
        }

        return view('ad_blocks.show')->with('adBlock', $adBlock);
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
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-blocks.index'));
        }

        return view('ad_blocks.edit')->with('adBlock', $adBlock);
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
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-blocks.index'));
        }

        $adBlock = $this->adBlockRepository->update($request->all(), $id);

        Flash::success('Ad Block updated successfully.');

        return redirect(route('ad-blocks.index'));
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
        $adBlock = $this->adBlockRepository->findWithoutFail($id);

        if (empty($adBlock)) {
            Flash::error('Ad Block not found');

            return redirect(route('ad-blocks.index'));
        }

        $this->adBlockRepository->delete($id);

        Flash::success('Ad Block deleted successfully.');

        return redirect(route('ad-blocks.index'));
    }
}
