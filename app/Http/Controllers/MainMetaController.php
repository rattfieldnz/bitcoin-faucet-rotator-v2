<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateMainMetaRequest;
use App\Http\Requests\UpdateMainMetaRequest;
use App\Models\Language;
use App\Repositories\MainMetaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return redirect(route('settings') . "#main-meta");
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

            return redirect(route('settings') . "#main-meta");
        }

        $input = $request->all();

        $this->mainMetaRepository->update($input, $id);

        flash('Main Meta updated successfully.')->success();

        return redirect(route('settings') . "#main-meta");
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
