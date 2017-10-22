<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PermissionController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class PermissionController extends AppBaseController
{
    private $permissionRepository;

    /**
     * PermissionController constructor.
     *
     * @param \App\Repositories\PermissionRepository $permissionRepo
     */
    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepository = $permissionRepo;
        $this->middleware('auth');
    }

    /**
     * Display the specified Permission.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'permissions.show', ['slug' => $slug], ['slug' => $slug]);
        $permission = $this->permissionRepository->findByField('slug', $slug)->first();

        if (empty($permission)) {
            flash('Permission not found.')->error();

            return redirect(route('permissions.index'));
        }

        return view('permissions.show')->with('permission', $permission);
    }

    /**
     * Show the form for editing the specified Permission.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'permissions.edit', ['slug' => $slug], ['slug' => $slug]);
        $permission = $this->permissionRepository->findByField('slug', $slug)->first();

        if (empty($permission)) {
            flash('Permission not found.')->error();

            return redirect(route('permissions.index'));
        }

        return view('permissions.edit')->with('permission', $permission);
    }

    /**
     * Update the specified Permission in storage.
     *
     * @param string                  $slug
     * @param UpdatePermissionRequest $request
     *
     * @return Response
     */
    public function update($slug, UpdatePermissionRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'permissions.update', ['slug' => $slug], ['slug' => $slug]);
        $permission = $this->permissionRepository->findByField('slug', $slug)->first();

        if (empty($permission)) {
            Flash::error('Permission not found');
            flash('Permission not found.')->error();

            return redirect(route('permissions.index'));
        }

        $this->permissionRepository->update($request->all(), $permission->id);

        flash('Permission updated successfully.')->success();

        return redirect(route('permissions.index'));
    }
}
