<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();

        if (empty($user)) {
            flash(
                "Sorry, your account was recently suspended or permanently deleted. 
                Please contact admin for further information."
            )->error();

            return redirect(route('login'));
        }

        return redirect(route('users.show', ['slug' => $user->slug]));
    }
}
