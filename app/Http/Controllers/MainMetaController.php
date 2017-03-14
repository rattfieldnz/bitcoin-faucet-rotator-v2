<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMainMetaRequest;
use App\Http\Requests\UpdateMainMetaRequest;
use App\Repositories\MainMetaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
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
        $this->mainMetaRepository->pushCriteria(new RequestCriteria($request));
        $mainMetas = $this->mainMetaRepository->all();

        return view('main_metas.index')
            ->with('mainMetas', $mainMetas);
    }

    /**
     * Show the form for creating a new MainMeta.
     *
     * @return Response
     */
    public function create()
    {
        return view('main_metas.create');
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
        $input = $request->all();

        $mainMeta = $this->mainMetaRepository->create($input);

        Flash::success('Main Meta saved successfully.');

        return redirect(route('mainMetas.index'));
    }

    /**
     * Display the specified MainMeta.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('mainMetas.index'));
        }

        return view('main_metas.show')->with('mainMeta', $mainMeta);
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
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('mainMetas.index'));
        }

        return view('main_metas.edit')->with('mainMeta', $mainMeta);
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
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('mainMetas.index'));
        }

        $mainMeta = $this->mainMetaRepository->update($request->all(), $id);

        Flash::success('Main Meta updated successfully.');

        return redirect(route('mainMetas.index'));
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
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            Flash::error('Main Meta not found');

            return redirect(route('mainMetas.index'));
        }

        $this->mainMetaRepository->delete($id);

        Flash::success('Main Meta deleted successfully.');

        return redirect(route('mainMetas.index'));
    }
}
