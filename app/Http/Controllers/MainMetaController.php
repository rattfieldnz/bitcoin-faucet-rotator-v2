<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateMainMetaRequest;
use App\Http\Requests\UpdateMainMetaRequest;
use App\Models\Language;
use App\Repositories\MainMetaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class MainMetaController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class MainMetaController extends AppBaseController
{
    private $mainMetaRepository;
    private $languageCodes;

    /**
     * MainMetaController constructor.
     *
     * @param \App\Repositories\MainMetaRepository $mainMetaRepo
     */
    public function __construct(MainMetaRepository $mainMetaRepo)
    {
        $this->mainMetaRepository = $mainMetaRepo;
        $this->languageCodes = Language::orderBy('name')->pluck('name', 'iso_code');

        $this->middleware('auth');
    }

    /**
     * Display a listing of the MainMeta.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.index', [], []);
        $this->mainMetaRepository->pushCriteria(new RequestCriteria($request));
        $mainMetas = $this->mainMetaRepository->all();

        if (count($mainMetas) == 0) {
            return view('main_meta.create');
        }
        $mainMeta = $this->mainMetaRepository->first();
        return view('main_meta.edit')
            ->with('mainMeta', $mainMeta)
            ->with('languageCodes', $this->languageCodes);
    }

    /**
     * Show the form for creating a new MainMeta.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.create', [], []);
        return view('main_meta.create')
            ->with('languageCodes', $this->languageCodes);
    }

    /**
     * Store a newly created MainMeta in storage.
     *
     * @param  CreateMainMetaRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateMainMetaRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.store', [], []);
        $input = $request->all();

        $this->mainMetaRepository->create($input);

        flash('Main Meta updated successfully.')->success();

        return redirect(route('main-meta.index'));
    }

    /**
     * Show the form for editing the specified MainMeta.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.edit', ['id' => $id], ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            flash('Main Meta not found.')->error();

            return redirect(route('main-metas.index'));
        }

        return view('main_meta.edit')
            ->with('mainMeta', $mainMeta)
            ->with('languageCodes', $this->languageCodes);
    }

    /**
     * Update the specified MainMeta in storage.
     *
     * @param  int                   $id
     * @param  UpdateMainMetaRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateMainMetaRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.update', ['id' => $id], ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            flash('Main Meta not found.')->error();

            return redirect(route('main-metas.index'));
        }

        $input = $request->all();

        $this->mainMetaRepository->update($input, $id);

        flash('Main Meta updated successfully.')->success();

        return redirect(route('main-meta.index'));
    }

    /**
     * Remove the specified MainMeta from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'main-meta.destroy', ['id' => $id], ['id' => $id]);
        $mainMeta = $this->mainMetaRepository->findWithoutFail($id);

        if (empty($mainMeta)) {
            flash('Main Meta not found.')->success();

            return redirect(route('main-metas.index'));
        }

        $this->mainMetaRepository->delete($id);

        flash('Main meta deleted successfully.')->success();

        return redirect(route('main-metas.index'));
    }
}
