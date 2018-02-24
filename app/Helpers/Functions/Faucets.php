<?php namespace App\Helpers\Functions;

use App\Helpers\Constants;
use App\Helpers\Social\Twitter;
use App\Http\Requests\CreateFaucetRequest;
use App\Http\Requests\UpdateFaucetRequest;
use App\Models\Faucet;
use App\Models\MainMeta;
use App\Models\User;
use App\Transformers\FaucetsTransformer;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Carbon\Carbon;
use Form;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Laracasts\Flash\Flash as LaracastsFlash;
use App\Models\PaymentProcessor;
use App\Repositories\FaucetRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;

/**
 * Class Faucets
 *
 * A helper class to handle extra functionality
 * related to currently stored faucets.
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers\Functions
 */
class Faucets
{
    private $faucetRepository;

    /**
     * Faucets constructor.
     *
     * @param \App\Repositories\FaucetRepository $faucetRepository
     */
    public function __construct(FaucetRepository $faucetRepository)
    {
        $this->faucetRepository = $faucetRepository;
    }

    /**
     * Create and store a new faucet.
     *
     * @param CreateFaucetRequest $request
     */
    public function createStoreFaucet(CreateFaucetRequest $request)
    {
        $input = $request->except('payment_processors', 'slug', 'referral_code', 'send_tweet');

        $faucet = $this->faucetRepository->create($input);

        $paymentProcessors = $request->get('payment_processors');
        $referralCode = $request->get('referral_code');

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        //$faucet->first()->paymentProcessors()->detach();

        if (count($paymentProcessors) >= 1) {
            foreach ($paymentProcessors as $paymentProcessorId) {
                $faucet->paymentProcessors()->attach((int)$paymentProcessorId);
            }
        }

        Users::adminUser()->faucets()->attach([$faucet->id => ['referral_code' => $referralCode]]);

        if ($request->get('send_tweet') == 1 && env('APP_ENV') == 'production') {
            $twitter = new Twitter(Users::adminUser());
            $tweet = self::renderTweet($faucet);

            $twitter->sendTweet($tweet);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        activity(self::faucetLogName())
            ->performedOn($faucet)
            ->causedBy(Auth::user())
            ->log("The faucet ':subject.name' was added to the collection by :causer.user_name");
    }

    /**
     * Create a user faucet via pivot table.
     *
     * @param array $data
     * @return void
     */
    public static function createStoreUserFaucet(array $data)
    {
        if (key_exists('user_id', $data) &&
            key_exists('faucet_id', $data) &&
            key_exists('referral_code', $data)
        ) {
            $userFaucetData = self::cleanUserFaucetInput($data);
            $userId = $userFaucetData['user_id'];
            $faucetId = $userFaucetData['faucet_id'];
            $referralCode = !empty($userFaucetData['referral_code']) ? $userFaucetData['referral_code'] : null;

            $user = User::where('id', $userId)->first();
            $faucet = Faucet::where('id', $faucetId)->first();

            if (!empty($user) && !empty($faucet)) {
                self::setUserFaucetRefCode($user, $faucet, $referralCode);

                activity(Faucets::faucetLogName())
                    ->performedOn($faucet)
                    ->causedBy(Auth::user())
                    ->log("The faucet ':subject.name' was added to '" . $user->user_name . "'s' collection by :causer.user_name");
            }
        }
    }

    /**
     * Update a user faucet via pivot table.
     *
     * @param array $data
     * @param       $userId
     * @return void
     */
    public static function updateUserFaucet(array $data, $userId)
    {

        if (key_exists('user_id', $data) &&
            key_exists('faucet_id', $data) &&
            key_exists('referral_code', $data)
        ) {
            $userFaucetData = self::cleanUserFaucetInput($data);
            $faucetId = $userFaucetData['faucet_id'];
            $referralCode = !empty($userFaucetData['referral_code']) ? $userFaucetData['referral_code'] : null;

            $user = User::where('id', $userId)->first();
            $faucet = Faucet::where('id', $faucetId)->first();

            if (!empty($user) && !empty($faucet)) {
                self::setUserFaucetRefCode($user, $faucet, $referralCode);

                activity(self::faucetLogName())
                    ->performedOn($faucet)
                    ->causedBy(Auth::user())
                    ->log("The faucet ':subject.name' in '" . $user->user_name . "'s' collection was updated by :causer.user_name");
            }
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        $data['payment_processor'] = Purifier::clean($data['payment_processor'], 'generalFields');
        return $data;
    }

    /**
     * Sanitize user faucet data via pivot table.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanUserFaucetInput(array $data)
    {
        return [
            'user_id' => Purifier::clean($data['user_id'], 'generalFields'),
            'faucet_id' => Purifier::clean($data['faucet_id'], 'generalFields'),
            'referral_code' => Purifier::clean($data['referral_code'], 'generalFields'),
        ];
    }

    /**
     * Update the specified faucet.
     *
     * @param  $slug
     * @param  UpdateFaucetRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateFaucet($slug, UpdateFaucetRequest $request)
    {
        $currentFaucet = $this->faucetRepository->findByField('slug', $slug, true)->first();

        $faucet = $this->faucetRepository->update($request->all(), $currentFaucet->id);

        $paymentProcessors = $request->get('payment_processors');
        $paymentProcessorIds = $request->get('payment_processors');

        $referralCodeData = $request->get('referral_code');
        $referralCode = !empty($referralCodeData) ? $referralCodeData : null;

        if (count($paymentProcessorIds) == 1) {
            $paymentProcessors = PaymentProcessor::where('id', $paymentProcessorIds[0]);
        } elseif (count($paymentProcessorIds) >= 1) {
            $paymentProcessors = PaymentProcessor::whereIn('id', $paymentProcessorIds);
        }

        if (empty($faucet)) {
            LaracastsFlash::error('Faucet not found');

            return redirect(route('faucets.index'));
        }

        $toAddPaymentProcessorIds = [];

        foreach ($paymentProcessors->pluck('id')->toArray() as $key => $value) {
            array_push($toAddPaymentProcessorIds, (int)$value);
        }

        if (count($toAddPaymentProcessorIds) > 1) {
            $faucet->paymentProcessors()->sync($toAddPaymentProcessorIds);
        } elseif (count($toAddPaymentProcessorIds) == 1) {
            $faucet->paymentProcessors()->sync([$toAddPaymentProcessorIds[0]]);
        }

        if (Auth::user()->hasRole('owner')) {
            $faucet->users()->sync([Auth::user()->id => ['faucet_id' => $faucet->id, 'referral_code' => $referralCode]]);
        }

        activity(self::faucetLogName())
            ->performedOn($faucet)
            ->causedBy(Auth::user())
            ->log("The faucet ':subject.name' was updated by :causer.user_name");
    }

    /**
     * Soft-delete or permanently delete a faucet.
     *
     * @param  $slug
     * @param  bool $permanentlyDelete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroyFaucet($slug, $permanentlyDelete = false)
    {
        $faucet = $this->faucetRepository->findByField('slug', $slug)->first();
        $logFaucet = $this->faucetRepository->findByField('slug', $slug)->first();
        $logMessage = null;

        if (empty($faucet)) {
            LaracastsFlash::error('Faucet not found');

            return redirect(route('faucets.index'));
        }

        if (!empty($faucet) && $faucet->isDeleted()) {
            LaracastsFlash::error('The faucet has already been deleted.');

            return redirect(route('faucets.index'));
        }

        if ($permanentlyDelete == true) {
            $logMessage = "The faucet ':subject.name' was permanently deleted by :causer.user_name";
            $faucet->forceDelete();
        } else {
            $this->faucetRepository->deleteWhere(['slug' => $slug]);
            $logMessage = "The faucet ':subject.name' was archived/deleted by :causer.user_name";
        }

        activity(self::faucetLogName())
            ->performedOn($logFaucet)
            ->causedBy(Auth::user())
            ->log($logMessage);
    }

    /**
     * Soft-delete or permanently delete a user's faucet and referral information.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Faucet $faucet
     * @param bool               $permanentlyDelete
     *
     * @return bool
     */
    public static function destroyUserFaucet(User $user, Faucet $faucet, bool $permanentlyDelete = false)
    {
        if (empty($user) || empty($faucet)) {
            return false;
        }

        if ($permanentlyDelete == false) {
            DB::table('referral_info')
                ->where('user_id', $user->id)
                ->where('faucet_id', $faucet->id)
                ->update(['deleted_at' => Carbon::now()]);

            activity(self::faucetLogName())
                ->performedOn($faucet)
                ->causedBy(Auth::user())
                ->log("The faucet ':subject.name' in '" . $user->user_name . "'s' collection was archived/deleted by :causer.user_name");
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::table('referral_info')
                ->where('user_id', $user->id)
                ->where('faucet_id', $faucet->id)
                ->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            activity(self::faucetLogName())
                ->performedOn($faucet)
                ->causedBy(Auth::user())
                ->log("The faucet ':subject.name' in '" . $user->user_name . "'s' collection was permanently deleted by :causer.user_name");
        }
    }

    /**
     * Restore a specified soft-deleted faucet.
     *
     * @param $slug
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreFaucet($slug)
    {
        $faucet = $this->faucetRepository->findByField('slug', $slug)->first();
        $logFaucet = $this->faucetRepository->findByField('slug', $slug)->first();

        if (empty($faucet)) {
            LaracastsFlash::error('Faucet not found');

            return redirect(route('faucets.index'));
        }

        if (!empty($faucet) && !$faucet->isDeleted()) {
            LaracastsFlash::error('The faucet has already been restored or is still active.');

            return redirect(route('faucets.index'));
        }

        $this->faucetRepository->restoreDeleted($slug);

        activity(self::faucetLogName())
            ->performedOn($logFaucet)
            ->causedBy(Auth::user())
            ->log("The faucet ':subject.name' was restored by :causer.user_name");
    }

    /**
     * @param User   $user
     * @param Faucet $faucet
     * @return string
     */
    public static function getUserFaucetRefCode(User $user, Faucet $faucet)
    {

        // Check if the user and faucet exists.

        if (empty($user) || $user->isDeleted()) {
            return null;
        }

        if (empty($faucet) || !empty($faucet->pivot->deleted_at)) {
            return null;
        }

        $referralCode = DB::table('referral_info')->where(
            [
                ['faucet_id', '=', $faucet->id],
                ['user_id', '=', $user->id]
            ]
        )->first();

        return $referralCode != null ? $referralCode->referral_code : null;
    }

    /**
     * @param User   $user
     * @param Faucet $faucet
     * @param string $refCode
     * @return null
     */
    public static function setUserFaucetRefCode(User $user, Faucet $faucet, $refCode = null)
    {

        $refCode = empty($refCode) ? null : Purifier::clean($refCode, 'generalFields');

        $f = DB::table('referral_info')
            ->where('faucet_id', '=', $faucet->id)
            ->where('user_id', '=', $user->id)
            ->get();

        if (empty($f) || count($f) == 0) {
            DB::table('referral_info')->insert(
                [
                    'faucet_id' => $faucet->id,
                    'user_id' => $user->id,
                    'referral_code' => $refCode
                ]
            );
        } else {
            DB::table('referral_info')->where(
                [
                    ['faucet_id', '=', $faucet->id],
                    ['user_id', '=', $user->id]
                ]
            )->update(['referral_code' => $refCode]);
        }
    }

    /**
     * Retrieve the faucets of a specified user.
     *
     * @param  User $user
     * @param  bool $isDeleted
     * @return \Illuminate\Support\Collection
     */
    public function getUserFaucets(User $user, bool $isDeleted = false)
    {
        if (empty($user)) {
            return null;
        }

        $userFaucetIds = $this->getUserFaucetIds($user, $isDeleted);

        $faucets = $user->faucets()->whereIn('id', $userFaucetIds)->get();

        return $faucets;
    }

    /**
     * Set CSP secure iframe rules for faucet.
     * I.E: https://content-security-policy.com/.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Faucet $faucet
     * @return void
     */
    public static function setSecureFaucetIframe(User $user, Faucet $faucet)
    {
        $childFramesConfig = Config::get('secure-headers.csp.child-src.allow');
        $framesConfig = Config::get('secure-headers.csp.frame-src.allow');

        if (!empty($user) && !empty($faucet)) {
            $faucetUrl = $faucet->url . Faucets::getUserFaucetRefCode($user, $faucet);

            array_push($childFramesConfig, parse_url($faucetUrl)['host']);
            array_push($framesConfig, parse_url($faucetUrl)['host']);

            Config::set('secure-headers.csp.child-src.allow', $childFramesConfig);
            Config::set('secure-headers.csp.frame-src.allow', $framesConfig);
        }
    }

    /**
     * Set CSP secure iframe rules for multiple faucets.
     * I.E: https://content-security-policy.com/.
     *
     * @param $faucets
     *
     * @return void
     */
    public static function setMultipleFaucetsCsp($faucets)
    {

        $config = Config::get('secure-headers.csp.child-src.allow');
        $framesConfig = Config::get('secure-headers.csp.frame-src.allow');

        foreach ($faucets as $f) {
            if ($f instanceof Faucet) {
                array_push($config, parse_url($f->url)['host']);
                array_push($framesConfig, parse_url($f->url)['host']);
            }
        }

        Config::set('secure-headers.csp.child-src.allow', $config);
        Config::set('secure-headers.csp.frame-src.allow', $framesConfig);
    }

    /**
     * Get faucets logging name.
     *
     * @return string
     */
    public static function faucetLogName(): string
    {
        if (Auth::user()->isAnAdmin()) {
            return Constants::ADMIN_FAUCET_LOG_NAME;
        } else {
            return Constants::USER_FAUCET_LOG_NAME;
        }
    }

    /**
     * Function to set meta data properties for SEO.
     *
     * @param \App\Models\Faucet $faucet
     * @param \App\Models\User   $user
     * @return void
     */
    public static function setMeta(Faucet $faucet, User $user)
    {
        if (!empty($faucet) && !empty($user)) {
            $title = $faucet->meta_title . " " . env('APP_TITLE_SEPARATOR') . " " . env('APP_TITLE_APPEND');
            $description = $faucet->meta_description;
            $keywords = array_map('trim', explode(',', $faucet->meta_keywords));
            $publishedTime = $faucet->created_at->toW3CString();
            $modifiedTime = $faucet->updated_at->toW3CString();
            $author = $user->fullName();
            $currentUrl = route('faucets.show', ['slug' => $faucet->slug]);
            $image = env('APP_URL') . '/assets/images/og/bitcoin.png';

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
                    'section' => 'Crypto Faucets',
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
     * @param \App\Models\Faucet $faucet
     *
     * @return null|string
     * @internal param \App\Models\User $user
     *
     */
    public static function htmlEditButton(Faucet $faucet)
    {
        if (empty($faucet)) {
            return null;
        }

        if (Auth::check() && Auth::user()->isAnAdmin()) {
            return Form::button(
                '<i class="glyphicon glyphicon-edit"></i>',
                [
                    'type' => 'button',
                    'class' => 'btn btn-default btn-xs',
                    'style' => 'display: inline-block;',
                    'onClick' => "location.href='" . route('faucets.edit', ['slug' => $faucet->slug]) . "'"
                ]
            );
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\Faucet $faucet
     *
     * @return null|string
     *
     */
    public static function deletePermanentlyForm(Faucet $faucet)
    {
        if (empty($faucet)) {
            return null;
        }

        if (Auth::check() && Auth::user()->isAnAdmin() && $faucet->isDeleted()) {
            $route = ['faucets.delete-permanently', $faucet->slug];

            $form = Form::open(['route' => $route, 'method' => 'delete', 'style' => 'display: inline-block;']);
            $form .= Form::button(
                '<i class="glyphicon glyphicon-trash"></i>',
                [
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'onclick' => "return confirm('Are you sure? The faucet will be PERMANENTLY deleted!')"
                    ]
            );
            $form .= Form::close();

            return $form;
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\Faucet $faucet
     *
     * @return null|string
     *
     */
    public static function restoreForm(Faucet $faucet)
    {
        if (empty($faucet)) {
            return null;
        }

        if (Auth::check() && Auth::user()->isAnAdmin() && $faucet->isDeleted()) {
            $form = Form::open(['route' => ['faucets.restore', $faucet->slug], 'method' => 'patch', 'style' => 'display: inline-block;']);

            $form .= Form::button(
                '<i class="glyphicon glyphicon-refresh"></i>',
                [
                             'type' => 'submit',
                             'class' => 'btn btn-info btn-xs',
                             'onclick' => "return confirm('Are you sure you want to restore this deleted faucet?')"
                         ]
            );
            $form .= Form::close();

            return $form;
        } else {
            return null;
        }
    }

    /**
     * @param \App\Models\Faucet $faucet
     *
     * @return null|string
     *
     */
    public static function softDeleteForm(Faucet $faucet)
    {
        if (empty($faucet)) {
            return null;
        }

        $form = null;

        if (Auth::check() && Auth::user()->isAnAdmin()) {
            $form = Form::open(['route' => ['faucets.destroy', $faucet->slug], 'method' => 'delete', 'style' => 'display: inline-block;']);
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

    /**
     * @param \App\Models\Faucet $faucet
     *
     * @return array|null
     *
     */
    public static function faucetData(Faucet $faucet)
    {
        if (empty($faucet)) {
            return null;
        }

        $data = [
            'name' => [
                'display' => route('faucets.show', ['slug' => $faucet->slug]),
                'original' => $faucet->name,
            ],
            'url' => $faucet->url . Faucets::getUserFaucetRefCode(Users::adminUser(), $faucet),
            'interval_minutes' => intval($faucet->interval_minutes),
            'min_payout' => [
                'display' => number_format(intval($faucet->min_payout)),
                'original' => intval($faucet->min_payout)
            ],
            'max_payout' => [
                'display' => number_format(intval($faucet->max_payout)),
                'original' => intval($faucet->max_payout)
            ],
            'comments' => $faucet->comments,
            'is_paused' => [
                'display' => $faucet->is_paused == true ? "Yes" : "No",
                'original' => $faucet->is_paused
            ],
            'slug' => $faucet->slug,
            'has_low_balance' => $faucet->has_low_balance,
        ];

        $paymentProcessors = $faucet->paymentProcessors()->get();

        if (count($paymentProcessors) != 0) {
            $data['payment_processors'] = [];
            foreach ($paymentProcessors as $p) {
                array_push(
                    $data['payment_processors'],
                    [
                        'name' => $p->name,
                        'url' => route('payment-processors.show', ['slug' => $p->slug])
                    ]
                );
            }
        }

        if (Auth::check() && Auth::user()->isAnAdmin()) {
            $data['id'] = intval($faucet->id);
            $data['is_deleted'] = [
                'display' => empty($faucet->deleted_at) ? "No" : "Yes",
                'original' => $faucet->deleted_at
            ];
            $data['actions'] = '';
            $data['actions'] .= Faucets::htmlEditButton($faucet);

            if ($faucet->isDeleted()) {
                $data['actions'] .= Faucets::deletePermanentlyForm($faucet);
                $data['actions'] .= Faucets::restoreForm($faucet);
            }

            $data['actions'] .= Faucets::softDeleteForm($faucet);
        }

        return $data;
    }

    /**
     * @param \App\Models\Faucet $faucet
     * @param \App\Models\User   $user
     *
     * @return array|null
     */
    public static function userFaucetData(Faucet $faucet, User $user)
    {
        if (empty($faucet) || empty($user)) {
            return null;
        }

        $referralCode = Faucets::getUserFaucetRefCode($user, $faucet);

        $data = null;

        if ((Auth::check() && (Auth::user()->id == $user->id || Auth::user()->isAnAdmin())) ||
            (!Auth::check() && !empty($referralCode))
        ) {
            $data = [
                'name' => [
                    'display' => route(
                        'users.faucets.show',
                        ['userSlug' => $user->slug, 'faucetSlug' => $faucet->slug]
                    ),
                    'original' => $faucet->name,
                ],
                'url' => $faucet->url . $referralCode,
                'referral_code' => $referralCode,
                'interval_minutes' => intval($faucet->interval_minutes),
                'min_payout' => [
                    'display' => number_format($faucet->min_payout),
                    'original' => intval($faucet->min_payout)
                ],
                'max_payout' => [
                    'display' => number_format($faucet->max_payout),
                    'original' => intval($faucet->max_payout)
                ],
                'comments' => $faucet->comments,
                'is_paused' => [
                    'display' => $faucet->is_paused == true ? "Yes" : "No",
                    'original' => $faucet->is_paused
                ],
                'slug' => $faucet->slug,
                'has_low_balance' => $faucet->has_low_balance,
            ];

            $paymentProcessors = $faucet->paymentProcessors()->get();

            if (count($paymentProcessors) != 0) {
                $data['payment_processors'] = [];
                foreach ($paymentProcessors as $p) {
                    array_push(
                        $data['payment_processors'],
                        [
                            'name' => $p->name,
                            'url' => route(
                                'users.payment-processors.faucets',
                                ['userSlug' => $user->slug, 'paymentProcessorSlug' => $p->slug]
                            )
                        ]
                    );
                }
            }

            if (Auth::check() && (Auth::user()->isAnAdmin() || Auth::user()->id == $user->id)) {
                $data['id'] = intval($faucet->id);

                $data['referral_code_form'] = Form::hidden('faucet_id[]', $faucet->id) .
                    Form::text(
                        'referral_code[]',
                        Faucets::getUserFaucetRefCode($user, $faucet),
                        ['class' => 'form-control', 'placeholder' => 'ABCDEF123456']
                    );
            }
        }

        return $data;
    }

    /**
     * @param \App\Models\Faucet $faucet
     *
     * @return string
     */
    public static function renderTweet(Faucet $faucet)
    {
        if (empty($faucet)) {
            return "";
        }

        $placeholders = [
            Constants::FAUCET_NAME_PLACEHOLDER => $faucet->name,
            Constants::FAUCET_INTERVAL_PLACEHOLDER => $faucet->interval_minutes,
            Constants::FAUCET_URL_PLACEHOLDER => route('faucets.show', ['slug' => $faucet->slug]),
            Constants::FAUCET_MIN_PAYOUT_PLACEHOLDER => $faucet->min_payout,
            Constants::FAUCET_MAX_PAYOUT_PLACEHOLDER => $faucet->max_payout
        ];

        $tweet = !empty($faucet->twitter_message) ? strtr($faucet->twitter_message, $placeholders) : "";
        return $tweet;
    }

    /**
     * Get faucets whose url's have not timed out.
     *
     * @param int                                $timedout
     *
     * @return \Illuminate\Support\Collection
     */
    public function excludeTimedOutFaucets(int $timedout = 5): Collection
    {

        $deleted = Auth::check() && Auth::user()->isAnAdmin() ? true : false;
        $faucets = $this->faucetRepository->findItemsWhere(
            ['is_paused' => false, 'has_low_balance' => false],
            ['*'],
            $deleted
        )->sortBy('interval_minutes')->values();

        $filtered = collect();

        foreach ($faucets as $f) {
            if (Http::urlTimeOut($f->url, $timedout) != false) {
                $filtered->push($f);
            }
        }

        return $filtered;
    }

    public static function faucetJsonResponse(Faucet $faucet, User $user)
    {
        if (empty($user)) {
            Response::json(ResponseUtil::makeResponse("User not found.", null));
        }

        if (empty($faucet)) {
            $message = "Faucet not found.";
            $json = ['status' => 'error', 'code' => 404, 'message' => 'Faucet not found.'];
        } else {
            $message = "Faucet retrieved successfully.";
            $json = (new FaucetsTransformer)->transform(
                $user,
                $faucet,
                true
            );
        }

        return Response::json(ResponseUtil::makeResponse($message, $json));
    }

    /**
     * Retrieve faucet ids matching with specified user.
     *
     * @param  User $user
     * @param  bool $isDeleted
     * @return \Illuminate\Support\Collection
     */
    private function getUserFaucetIds(User $user, bool $isDeleted = false)
    {
        if ($isDeleted == true) {
            $userFaucetIds = DB::table('referral_info')->where(
                [
                    ['user_id', '=', $user->id],
                ]
            );
        } else {
            $userFaucetIds = DB::table('referral_info')->where(
                [
                    ['user_id', '=', $user->id],
                    ['deleted_at', '=', null]
                ]
            );
        }
        return $userFaucetIds->get()->pluck('faucet_id');
    }
}
