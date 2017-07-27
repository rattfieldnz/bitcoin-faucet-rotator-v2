<?php

namespace App\Http\Controllers;

use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreatePrivacyPolicyRequest;
use App\Http\Requests\UpdatePrivacyPolicyRequest;
use App\Repositories\PrivacyPolicyRepository;
use Helpers\Functions\Users;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PrivacyPolicyController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class PrivacyPolicyController extends AppBaseController
{
    /** @var  PrivacyPolicyRepository */
    private $privacyPolicyRepository;

    public function __construct(PrivacyPolicyRepository $privacyPolicyRepo)
    {
        $this->privacyPolicyRepository = $privacyPolicyRepo;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the PrivacyPolicy.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View|\Response
     */
    public function index(Request $request)
    {
        $this->privacyPolicyRepository->pushCriteria(new RequestCriteria($request));
        $privacyPolicy = $this->privacyPolicyRepository->first();

        if(count($privacyPolicy) == 0){
            if(!empty(Auth::user()) && Auth::user()->isAnAdmin()){
                flash(
                    '<i class="fa fa-info-circle" aria-hidden="true" style="font-size: 2em; margin-right: 0.5em;"></i> 
                    You have not created a privacy policy yet; however, you can create one here.'
                )->info();
                return redirect(route('privacy-policy.create'));
            } else {
                $errorMessage = "The admin has not created a privacy policy; however, you can request them to create one.";
                return view('privacy_policy.show')
                    ->with('privacyPolicy', null)
                    ->with('errorMessage', $errorMessage);
            }
        } else {
            $title = !empty($privacyPolicy->title) ? $privacyPolicy->title : "Privacy Policy";
            $description = $privacyPolicy->short_description;
            $keywords = array_map('trim', explode(',', $privacyPolicy->keywords));
            $publishedTime = $privacyPolicy->created_at->toW3CString();
            $modifiedTime = $privacyPolicy->updated_at->toW3CString();
            $author = Users::adminUser()->fullName();
            $currentUrl = route('privacy-policy.index');
            $image = env('APP_URL') . '/assets/images/og/bitcoin.png';
            WebsiteMeta::setCustomMeta(
                $title, $description, $keywords,
                $publishedTime, $modifiedTime, $author,
                $currentUrl, $image, "Privacy Policy"
            );
            return view('privacy_policy.show')
                ->with('privacyPolicy', $privacyPolicy);
        }
    }

    /**
     * Show the form for creating a new PrivacyPolicy.
     *
     * @return Response
     */
    public function create()
    {
        return view('privacy_policy.create');
    }

    /**
     * Store a newly created PrivacyPolicy in storage.
     *
     * @param CreatePrivacyPolicyRequest $request
     *
     * @return Response
     */
    public function store(CreatePrivacyPolicyRequest $request)
    {
        $input = $request->all();

        $privacyPolicy = $this->privacyPolicyRepository->create($input);

        Flash::success('Privacy Policy saved successfully.');

        return redirect(route('privacy-policy.index'));
    }

    /**
     * Display the specified PrivacyPolicy.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $privacyPolicy = $this->privacyPolicyRepository->first();

        if (empty($privacyPolicy)) {
            Flash::error('Privacy Policy not found');

            return redirect(route('privacy-policy.index'));
        }

        return view('privacy_policies.show')->with('privacyPolicy', $privacyPolicy);
    }

    /**
     * Show the form for editing the specified PrivacyPolicy.
     *
     * @return \Illuminate\View\View|\Response
     *
     */
    public function edit()
    {
        $privacyPolicy = $this->privacyPolicyRepository->first();

        if(count($privacyPolicy) == 0){
            if(!empty(Auth::user()) && Auth::user()->isAnAdmin()){
                flash(
                    '<i class="fa fa-info-circle" aria-hidden="true" style="font-size: 2em; margin-right: 0.5em;"></i> 
                            You have not created a privacy policy yet; however, you can create one here.'
                )->info();
                return redirect(route('privacy-policy.create'));
            } else {
                $errorMessage = "The admin has not created a privacy policy; however, you can request them to create one.";
                return view('privacy_policy.show')
                    ->with('privacyPolicy', null)
                    ->with('errorMessage', $errorMessage);
            }
        }

        return view('privacy_policy.edit')->with('privacyPolicy', $privacyPolicy);
    }

    /**
     * Update the specified PrivacyPolicy in storage.
     *
     * @param  int              $id
     * @param UpdatePrivacyPolicyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrivacyPolicyRequest $request)
    {
        $privacyPolicy = $this->privacyPolicyRepository->findWithoutFail($id);

        if (empty($privacyPolicy)) {
            Flash::error('Privacy Policy not found');

            return redirect(route('privacy-policy.index'));
        }

        $privacyPolicy = $this->privacyPolicyRepository->update($request->all(), $id);

        Flash::success('Privacy Policy updated successfully.');

        return redirect(route('privacy-policy.index'));
    }
}
