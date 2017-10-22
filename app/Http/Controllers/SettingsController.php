<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\User;
use App\Repositories\AdBlockRepository;
use App\Repositories\MainMetaRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TwitterConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    private $mainMetaRepository;
    private $adBlockRepository;
    private $twitterConfigRepository;
    private $roleRepository;
    private $permissionRepository;
    private $languageCodes;

    public function __construct(
        MainMetaRepository $mainMetaRepo,
        AdBlockRepository $adBlockRepo,
        TwitterConfigRepository $twitterConfigRepo,
        RoleRepository $roleRepo,
        PermissionRepository $permissionRepo
    )
    {
        $this->mainMetaRepository = $mainMetaRepo;
        $this->adBlockRepository = $adBlockRepo;
        $this->twitterConfigRepository = $twitterConfigRepo;
        $this->roleRepository = $roleRepo;
        $this->permissionRepository = $permissionRepo;
        $this->languageCodes = Language::orderBy('name')->pluck('name', 'iso_code');

        $this->middleware('auth');
    }

    public function index(){
        if(!Auth::user()->isAnAdmin()){
            abort(403);
        }
        $adminUser = User::where('is_admin', true)->first();
        $mainMetas = $this->mainMetaRepository->all();
        $mainMetaView = 'main_meta.create';
        $mainMeta = $this->mainMetaRepository->first();

        if (count($mainMetas) > 0) {
            $mainMetaView = 'main_meta.edit';
        }

        return view('settings.index')
            ->with('mainMeta', $mainMeta)
            ->with('mainMetaView', $mainMetaView)
            ->with('languageCodes', $this->languageCodes);
    }
}
