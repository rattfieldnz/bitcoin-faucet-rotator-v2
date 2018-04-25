<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 3/04/2017
 * Time: 20:33
 */

namespace App\Helpers\Functions;

use App\Helpers\Constants;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\PaymentProcessor;
use App\Repositories\UserRepository;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Creativeorange\Gravatar\Facades\Gravatar;
use Exception;
use Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash as LaracastsFlash;
use App\Helpers\Functions\Faucets;

/**
 * Class Users
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package Helpers\Functions
 */
class Users
{
    private $userRepository;
    private $faucetFunctions;

    /**
     * Users constructor.
     *
     * @param \App\Repositories\UserRepository $userRepository
     * @param \App\Helpers\Functions\Faucets   $faucetFunctions
     */
    public function __construct(UserRepository $userRepository, Faucets $faucetFunctions)
    {
        $this->userRepository = $userRepository;
        $this->faucetFunctions = $faucetFunctions;
    }

    /**
     * Create a user with form data.
     *
     * @param  array $data
     * @return User
     */
    public function createStoreUser(array $data)
    {
        $user = $this->userRepository->create($data);

        $newUser = User::where('slug', $user->slug)->first();

        $userRole = Role::where('name', 'user')->first();
        $newUser->attachRole($userRole);

        $initialPermissions = [
            Permission::where('name', 'read-users')->first(),
            Permission::where('name', 'read-faucets')->first(),
            Permission::where('name', 'create-user-faucets')->first(),
            Permission::where('name', 'read-user-faucets')->first(),
            Permission::where('name', 'update-user-faucets')->first(),
            Permission::where('name', 'soft-delete-user-faucets')->first(),
            Permission::where('name', 'permanent-delete-user-faucets')->first(),
            Permission::where('name', 'restore-user-faucets')->first(),
            Permission::where('name', 'read-payment-processors')->first(),
        ];

        foreach ($initialPermissions as $permission) {
            $newUser->attachPermission($permission);
        }

        $faucets = Faucet::all();

        foreach ($faucets as $f) {
            $f->users()->attach($user->id, ['faucet_id' => $f->id, 'referral_code' => null]);
        }

        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            $logMessage = "The user ':subject.user_name' was added by :causer.user_name";
            $causedBy = Auth::user();
        } else {
            $logMessage = "User ':subject.user_name' has successfully registered.";
            $causedBy = $user;
        }

        activity(self::userLogName())
            ->performedOn($user)
            ->causedBy($causedBy)
            ->log($logMessage);

        return $user;
    }

    /**
     * Delete a user.
     *
     * @param  $slug
     * @param  bool $permanentlyDelete True if user permanently deleted, false for soft-delete.
     * @return bool|int
     */
    public function destroyUser($slug, bool $permanentlyDelete = false)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $logMessage = null;

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && !$user->hasRole('owner') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            if ($permanentlyDelete == false) {
                if (Auth::user()->isAnAdmin()) {
                    $logMessage = "The user ':subject.user_name' was suspended by ':causer.user_name'";
                } else {
                    $logMessage = "User ':subject.user_name' has deleted their account";
                }
            } else {
                if (Auth::user()->isAnAdmin()) {
                    $logMessage = "The user ':subject.user_name' was permanently deleted by ':causer.user_name'";
                }
            }

            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log($logMessage);

            return $this->userRepository->deleteWhere(['slug' => $slug], $permanentlyDelete);
        } else {
            return false;
        }
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param  $slug
     * @return bool|mixed
     */
    public function restoreUser($slug)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log("The user ':subject.user_name' was restored by :causer.user_name");

            return $this->userRepository->restoreDeleted($slug);
        } else {
            return false;
        }
    }

    /**
     * Purge archived users
     *
     * @return array
     */
    public function purgeArchivedUsers()
    {

        $userIds = $this->userRepository->onlyTrashed()->get()->pluck('id')->toArray();

        DB::table('referral_info')
            ->whereIn('user_id', $userIds)
            ->delete();

        DB::table('users')
            ->whereIn('id', $userIds)
            ->delete();

        return ['purged' => true, 'count' => count($userIds)];
    }

    /**
     * Update a specified user.
     *
     * @param  $slug
     * @param  UpdateUserRequest $request
     * @return bool|mixed
     */
    public function updateUser($slug, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findByField('slug', $slug)->first();
        $logMessage = null;

        if (empty($user) || ($user == Auth::user() && $user->hasRole('user') && $user->isDeleted() == true)) {
            return false;
        }
        if ($user == Auth::user() || Auth::user()->isAnAdmin()) {
            $updateRequestData = $request->has('password') &&
            $request->has('password_confirmation') ?
                $request->all() :
                $request->except(['password','password_confirmation']);

            if (Auth::user()->isAnAdmin()) {
                if ($user == Auth::user()) {
                    $logMessage = "The ':subject.user_name' has updated their profile";
                } else {
                    $logMessage = "The user ':subject.user_name' was updated by :causer.user_name";
                }
            } else {
                $logMessage = "':subject.user_name' has updated their profile";
            }

            activity(self::userLogName())
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->log($logMessage);

            return $this->userRepository->update(
                $updateRequestData,
                $user->slug
            );
        } else {
            return false;
        }
    }

    /**
     * Checks if a user is authorised to access a route.
     *
     * @param  User       $user
     * @param  $routeName
     * @param  array      $routeParameters
     * @param  array|null $dataParameters
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function userCanAccessArea(User $user, $routeName, array $routeParameters, array $dataParameters = [])
    {
        if (!$user->isAnAdmin()) {
            abort(403);
        }
        $currentRoute = route($routeName, $routeParameters);
        if (!$currentRoute) {
            abort(404);
        }
        return redirect($currentRoute)->with($dataParameters);
    }

    /**
     * Retrieve faucets associated with a payment processor.
     *
     * @param  User             $user
     * @param  PaymentProcessor $paymentProcessor
     * @param  bool             $trashed
     * @return array
     */
    public function getPaymentProcessorFaucets(User $user, PaymentProcessor $paymentProcessor, $trashed = false)
    {
        if (empty($user) || empty($paymentProcessor)) {
            abort(404);
        }

        $faucets = $this->faucetFunctions->getUserFaucets($user, $trashed);

        $paymentProcessorFaucets = null;

        if ($trashed == true) {
            $paymentProcessorFaucets = $paymentProcessor->faucets()->withTrashed()->pluck('id');
        } else {
            $paymentProcessorFaucets = $paymentProcessor->faucets()->pluck('id');
        }

        $userFaucets = $faucets->whereIn('id', $paymentProcessorFaucets)->all();

        return $userFaucets;
    }

    public function userLogName(): string
    {

        if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
            return Constants::ADMIN_USER_MANAGEMENT_LOG;
        } else {
            return Constants::USER_MANAGEMENT_LOG;
        }
    }

    public static function adminUser()
    {
        $user = User::where('slug', '=', Constants::ADMIN_SLUG)
                    ->where('is_admin', '=', true)
                    ->first();
        return $user->isAnAdmin() ? $user : null;
    }

    /**
     * Function to set meta data properties for SEO.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public static function setMeta(User $user)
    {
        if (!empty($user)) {
            $title = "User '" . $user->user_name . "'s Profile" . " " . env('APP_TITLE_SEPARATOR') . " " . env('APP_TITLE_APPEND');
            $description = "View the user profile for '" . $user->user_name .
                "' on this page. They currently have " . count($user->faucets()->get()) . " faucets.";
            $keywords = [$user->fullName(), $user->user_name, "User Profile", "User Faucets", "Show User Profile"];
            $publishedTime = $user->created_at->toW3CString();
            $modifiedTime = $user->updated_at->toW3CString();
            $author = $user->fullName();
            $currentUrl = route('users.show', ['slug' => $user->slug]);
            $image = env('APP_URL') . '/assets/images/og/bitcoin.png';
            $categoryDescription = "User Profile";

            SEOMeta::setTitle($title)
                ->setTitleSeparator('|')
                ->setDescription($description)
                ->setKeywords($keywords)
                ->addMeta('author', $author, 'name')
                ->addMeta('revised', $modifiedTime, 'name')
                ->addMeta('name', $title, 'itemprop')
                ->addMeta('description', $description, 'itemprop')
                ->addMeta('image', $image, 'itemprop')
                ->addMeta('fb:admins', "871754942861947", 'property')
                ->setCanonical($currentUrl);

            OpenGraph::setTitle($title)
                ->setUrl($currentUrl)
                ->setSiteName(MainMeta::first()->page_main_title)
                ->addProperty("locale", MainMeta::first()->language()->first()->isoCode())
                ->setDescription($description)
                ->setType('article')
                ->addImage($image)
                ->setArticle([
                    'author' => $author,
                    'published_time' => $publishedTime,
                    'modified_time' => $modifiedTime,
                    'section' => $categoryDescription,
                    'tag' => $keywords
                ]);

            TwitterCard::setType('summary')
                ->addImage($image)
                ->setTitle($title)
                ->setDescription($description)
                ->setUrl($currentUrl)
                ->setSite(MainMeta::first()->twitter_username);
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public static function getGravatar(User $user)
    {
        if (empty($user)) {
            return null;
        }
        return Gravatar::get($user->email);
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getFaucets(User $user): Collection
    {
        $faucets = $user->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('faucets.deleted_at', '=', null);

        if (Auth::check() && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
            return $faucets->orderBy('faucets.interval_minutes')->get();
        } else {
            return $faucets->wherePivot('referral_code', '!=', null)
                ->orderBy('faucets.interval_minutes')
                ->get();
        }
    }

    /**
     * @param \App\Models\User $user
     * @param                  $faucetSlug
     *
     * @return \App\Models\Faucet|null
     */
    public static function getFaucet(User $user, $faucetSlug)
    {
        $faucet = $user->faucets()
            ->where('faucets.is_paused', '=', false)
            ->where('faucets.has_low_balance', '=', false)
            ->where('slug', '=', $faucetSlug);

        if (!empty($faucet)) {
            if (Auth::check() && (Auth::user()->isAnAdmin() || $user === Auth::user())) {
                return $faucet->first();
            } else {
                return $faucet
                    ->wherePivot('referral_code', '!=', null)
                    ->first();
            }
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return null|string
     *
     */
    public static function htmlEditButton(User $user)
    {
        if (empty($user)) {
            return null;
        }

        if (Auth::check() && (Auth::user()->isAnAdmin() || Auth::user() === $user)) {
            $route = route('users.edit', ['slug' => $user->slug]);

            return Form::button(
                '<i class="glyphicon glyphicon-edit"></i>',
                [
                    'type' => 'button',
                    'class' => 'btn btn-default btn-xs',
                    'style' => 'display: inline-block;',
                    'onClick' => "location.href='" . $route . "'"
                ]
            );
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return null|string
     *
     */
    public static function deletePermanentlyForm(User $user)
    {
        if (empty($user)) {
            return null;
        }

        if ($user->isDeleted()) {
            $route = ['users.delete-permanently', $user->slug];

            $form = Form::open(['route' => $route, 'method' => 'delete', 'style' => 'display: inline-block;']);
            $form .= Form::button(
                '<i class="glyphicon glyphicon-trash"></i>',
                [
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-xs',
                    'onclick' => "return confirm('Are you sure? The user will be PERMANENTLY deleted!')"
                ]
            );
            $form .= Form::close();

            return $form;
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return null|string
     *
     */
    public static function restoreForm(User $user)
    {
        if (empty($user)) {
            return null;
        }

        if (Auth::check() && (Auth::user()->isAnAdmin()) && $user->isDeleted()) {
            $route = ['users.restore', $user->slug];
            $form = Form::open(['route' => $route, 'method' => 'patch', 'style' => 'display: inline-block;']);

            $form .= Form::button(
                '<i class="glyphicon glyphicon-refresh"></i>',
                [
                    'type' => 'submit',
                    'class' => 'btn btn-info btn-xs',
                    'onclick' => "return confirm('Are you sure you want to restore this deleted user?')"
                ]
            );
            $form .= Form::close();

            return $form;
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return null|string
     * @internal param \App\Models\Faucet $faucet
     *
     */
    public static function softDeleteForm(User $user)
    {
        if (empty($user)) {
            return null;
        }

        $form = null;

        if (Auth::check() && (Auth::user()->isAnAdmin()) && !$user->isDeleted()) {
            $route = ['users.destroy', $user->slug];

            $form = Form::open(['route' => $route, 'method' => 'delete', 'style' => 'display: inline-block;']);
            $form .= Form::button(
                '<i class="glyphicon glyphicon-trash"></i>',
                [
                    'type' => 'submit',
                    'class' => 'btn btn-warning btn-xs',
                    'onclick' => "return confirm('Are you sure?')"
                ]
            );
            $form .= Form::close();
        }

        return $form;
    }
}
