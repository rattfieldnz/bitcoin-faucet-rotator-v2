<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateMainMetaRequest;
use App\Http\Requests\UpdateMainMetaRequest;
use App\Repositories\MainMetaRepository;
use App\Http\Controllers\AppBaseController;
use Chromabits\Purifier\Contracts\Purifier;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class MainMetaController extends AppBaseController
{
    /** @var  MainMetaRepository */
    private $mainMetaRepository;

    public function __construct(MainMetaRepository $mainMetaRepo)
    {
        $this->mainMetaRepository = $mainMetaRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the MainMeta.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.index');
        $this->mainMetaRepository->pushCriteria(new RequestCriteria($request));
        $mainMetas = $this->mainMetaRepository->all();

        if (count($mainMetas) == 0) {
            return view('main_meta.create');
        }
        $mainMeta = $this->mainMetaRepository->first();
        return view('main_meta.edit')
            ->with('mainMeta', $mainMeta);
    }

    /**
     * Show the form for creating a new MainMeta.
     *
     * @return Response
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.create');
        return view('main_meta.create');
    }

    /**
     * Store a newly created MainMeta in storage.
     *
     * @param CreateMainMetaRequest $request
     *
     * @return Response
     */
    public function store(CreateMainMetaRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.store');
        $input = $request->all();

        $mainMeta = $this->mainMetaRepository->create($input);

        Flash::success('Main Meta saved successfully.');

        return redirect(route('main-meta.index'));
    }

    /**
     * Show the form for editing the specified MainMeta.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.edit', ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('main-metas.index'));
        }

        return view('main_meta.edit')->with('mainMeta', $mainMeta);
    }

    /**
     * Update the specified MainMeta in storage.
     *
     * @param  int              $id
     * @param UpdateMainMetaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMainMetaRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.update', ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('main-metas.index'));
        }

        $input = $request->all();

        $mainMeta = $this->mainMetaRepository->update($input, $id);

        Flash::success('Main Meta updated successfully.');

        return redirect(route('main-meta.index'));
    }

    /**
     * Remove the specified MainMeta from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.destroy', ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('main-metas.index'));
        }

        $this->mainMetaRepository->delete($id);

        Flash::success('Main Meta deleted successfully.');

        return redirect(route('main-metas.index'));
    }
}
