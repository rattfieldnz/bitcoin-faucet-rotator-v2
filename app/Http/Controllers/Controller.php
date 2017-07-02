<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Class Controller
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Custom function to logout users.
     */
    protected function logoutAuth()
    {
        $this->guard()->logout();
    }

    /**
     * Clear active sessions.
     * Useful for logging users out from authentication.
     *
     * @param Request $request
     */
    protected function clearSessions(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
    }
}
