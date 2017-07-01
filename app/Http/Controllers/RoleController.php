<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\RoleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RoleController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class RoleController extends AppBaseController
{
    private $roleRepository;

    /**
     * RoleController constructor.
     *
     * @param \App\Repositories\RoleRepository $roleRepo
     */
    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the Role.
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.index', [], []);
        $this->roleRepository->pushCriteria(new RequestCriteria($request));
        $roles = $this->roleRepository->all();

        return view('roles.index')
            ->with('roles', $roles);
    }

    /**
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    /*public function create()
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.create');
        return view('roles.create');
    }*/

    /**
     * Store a newly created Role in storage.
     *
     * @param CreateRoleRequest $request
     *
     * @return Response
     */
    /*public function store(CreateRoleRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.store');
        $input = $request->all();

        $role = $this->roleRepository->create($input);

        Flash::success('Role saved successfully.');

        return redirect(route('roles.index'));
    }*/

    /**
     * Display the specified Role.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.show', ['slug' => $slug], ['slug' => $slug]);
        $role = $this->roleRepository->findByField('slug', $slug)->first();

        if (empty($role)) {
            flash('Role not found.')->error();

            return redirect(route('roles.index'));
        }

        return view('roles.show')->with('role', $role);
    }

    /**
     * Show the form for editing the specified Role.
     *
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($slug)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.edit', ['slug' => $slug], ['slug' => $slug]);
        $role = $this->roleRepository->findByField('slug', $slug)->first();

        if (empty($role)) {
            flash('Role not found.')->error();

            return redirect(route('roles.index'));
        }

        return view('roles.edit')->with('role', $role);
    }

    /**
     * Update the specified Role in storage.
     *
     * @param string            $slug
     * @param UpdateRoleRequest $request
     *
     * @return Response
     */
    public function update($slug, UpdateRoleRequest $request)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.update', ['slug' => $slug], ['slug' => $slug]);
        $role = $this->roleRepository->findByField('slug', $slug)->first();

        if (empty($role)) {
            flash('Role not found.')->error();

            return redirect(route('roles.index'));
        }

        $this->roleRepository->update($request->all(), $role->id);

        flash('Role updated successfully.')->success();

        return redirect(route('roles.index'));
    }

    /**
     * Remove the specified Role from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    /*public function destroy($id)
    {
        Functions::userCanAccessArea(Auth::user(), 'roles.destroy');
        $role = $this->roleRepository->findWithoutFail($id);

        if (empty($role)) {
            Flash::error('Role not found');

            return redirect(route('roles.index'));
        }

        $this->roleRepository->delete($id);

        Flash::success('Role deleted successfully.');

        return redirect(route('roles.index'));
    }*/
}
