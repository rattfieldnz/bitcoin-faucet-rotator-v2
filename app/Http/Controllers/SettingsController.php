<?php

namespace App\Http\Controllers;

use App\Helpers\Functions\Users;
use App\Helpers\Social\SocialNetworkLinks;
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
    private $socialLinks;

    public function __construct(
        MainMetaRepository $mainMetaRepo,
        AdBlockRepository $adBlockRepo,
        TwitterConfigRepository $twitterConfigRepo,
        RoleRepository $roleRepo,
        PermissionRepository $permissionRepo
    ) {
    
        $this->mainMetaRepository = $mainMetaRepo;
        $this->adBlockRepository = $adBlockRepo;
        $this->twitterConfigRepository = $twitterConfigRepo;
        $this->roleRepository = $roleRepo;
        $this->permissionRepository = $permissionRepo;
        $this->languageCodes = Language::orderBy('name')->pluck('name', 'iso_code');
        $this->socialLinks = Users::adminUser() != null ? Users::adminUser()->socialNetworkLinks()->first(): null;

        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->isAnAdmin()) {
            abort(403);
        }

        $adminUser = Users::adminUser();
        $mainMeta = $this->mainMetaRepository->first();
        $adBlock = $this->adBlockRepository->first();
        $twitterConfig = $this->twitterConfigRepository->first();
        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();

        return view('settings.index')
            ->with('mainMeta', $mainMeta)
            ->with('adBlock', $adBlock)
            ->with('twitterConfig', $twitterConfig)
            ->with('roles', $roles)
            ->with('permissions', $permissions)
            ->with('adminUser', $adminUser)
            ->with('languageCodes', $this->languageCodes)
            ->with('socialLinks', $this->socialLinks);
    }
}
