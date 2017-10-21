<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Functions\Users;
use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Controllers\Controller;
use App\Libraries\Seo\SeoConfig;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Class ForgotPasswordController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\Auth
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $seoConfig = new SeoConfig();
        $seoConfig->title = "Reset Password";
        $seoConfig->description = "Current users can use this form to reset their password.";
        $seoConfig->keywords = [
            "Forgotten Password",
            "Password Reset",
            "Reset User Password"
        ];
        $seoConfig->publishedTime = Carbon::now()->toW3cString();
        $seoConfig->modifiedTime = Carbon::now()->toW3cString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('password.request');
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "User Credentials";
        WebsiteMeta::setCustomMeta($seoConfig);

        return view('auth.passwords.email');
    }
}
