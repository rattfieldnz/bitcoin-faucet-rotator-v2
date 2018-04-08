<?php

namespace App\Http\Controllers;

use App\Helpers\WebsiteMeta\WebsiteMeta;
use App\Http\Requests\CreateTermsAndConditionsRequest;
use App\Http\Requests\UpdateTermsAndConditionsRequest;
use App\Libraries\Seo\SeoConfig;
use App\Repositories\TermsAndConditionsRepository;
use App\Helpers\Functions\Users;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

/**
 * Class TermsAndConditionsController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers
 */
class TermsAndConditionsController extends AppBaseController
{
    /** @var  TermsAndConditionsRepository */
    private $termsAndConditionsRepository;

    public function __construct(TermsAndConditionsRepository $termsAndConditionsRepo)
    {
        $this->termsAndConditionsRepository = $termsAndConditionsRepo;
    }

    /**
     * Display a listing of the TermsAndConditions.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View|\Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->termsAndConditionsRepository->pushCriteria(new RequestCriteria($request));
        $termsAndConditions = $this->termsAndConditionsRepository->first();

        if (count($termsAndConditions) == 0) {
            if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
                flash(
                    '<i class="fa fa-info-circle" aria-hidden="true" style="font-size: 2em; margin-right: 0.5em;"></i> 
                    You have not created a set of terms and conditions yet; however, you can create them here.'
                )->info();
                return redirect(route('terms-and-conditions.create'));
            } else {
                $errorMessage = "The admin has not created a set of terms and conditions yet; however, you can request them to create them.";
                return view('terms_and_conditions.show')
                    ->with('termsAndConditions', null)
                    ->with('errorMessage', $errorMessage);
            }
        } else {
            $seoConfig = new SeoConfig();
            $seoConfig->title = !empty($termsAndConditions->title) ? $termsAndConditions->title : "Terms and Conditions";
            $seoConfig->description = $termsAndConditions->short_description;
            $seoConfig->keywords = array_map('trim', explode(',', $termsAndConditions->keywords));
            $seoConfig->publishedTime = $termsAndConditions->created_at->toW3CString();
            $seoConfig->modifiedTime = $termsAndConditions->updated_at->toW3CString();
            $seoConfig->authorName = Users::adminUser()->fullName();
            $seoConfig->currentUrl = route('terms-and-conditions');
            $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
            $seoConfig->categoryDescription = "Terms and Conditions";
            WebsiteMeta::setCustomMeta($seoConfig);

            return view('terms_and_conditions.show')
                ->with('termsAndConditions', $termsAndConditions);
        }
    }

    /**
     * Show the form for creating a new TermsAndConditions.
     *
     * @return Response
     */
    public function create()
    {
        return view('terms_and_conditions.create');
    }

    /**
     * Store a newly created TermsAndConditions in storage.
     *
     * @param CreateTermsAndConditionsRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateTermsAndConditionsRequest $request)
    {
        $input = $request->all();

        $termsAndConditions = $this->termsAndConditionsRepository->create($input);

        Flash::success('Terms And Conditions saved successfully.');

        return redirect(route('terms-and-conditions.index'));
    }

    /**
     * Display the specified TermsAndConditions.
     *
     * @return \Response
     */
    public function show()
    {
        $termsAndConditions = $this->termsAndConditionsRepository->first();

        if (empty($termsAndConditions)) {
            Flash::error('Terms And Conditions not found');

            return redirect(route('terms-and-conditions.create'));
        }

        $seoConfig = new SeoConfig();
        $seoConfig->title = !empty($termsAndConditions->title) ? $termsAndConditions->title : "Terms and Conditions";
        $seoConfig->description = $termsAndConditions->short_description;
        $seoConfig->keywords = array_map('trim', explode(',', $termsAndConditions->keywords));
        $seoConfig->publishedTime = $termsAndConditions->created_at->toW3CString();
        $seoConfig->modifiedTime = $termsAndConditions->updated_at->toW3CString();
        $seoConfig->authorName = Users::adminUser()->fullName();
        $seoConfig->currentUrl = route('terms-and-conditions');
        $seoConfig->imagePath = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $seoConfig->categoryDescription = "Terms and Conditions";
        WebsiteMeta::setCustomMeta($seoConfig);

        return view('terms_and_conditions.show')
            ->with('termsAndConditions', $termsAndConditions);
    }

    /**
     * Show the form for editing the specified TermsAndConditions.
     *
     * @return \Illuminate\View\View|\Response
     */
    public function edit()
    {

        $termsAndConditions = $this->termsAndConditionsRepository->first();

        if (count($termsAndConditions) == 0) {
            if (!empty(Auth::user()) && Auth::user()->isAnAdmin()) {
                flash(
                    '<i class="fa fa-info-circle" aria-hidden="true" style="font-size: 2em; margin-right: 0.5em;"></i> 
                            You have not created a set of terms and conditions yet; however, you can create them here.'
                )->info();
                return redirect(route('terms-and-conditions.create'));
            } else {
                $errorMessage = "The admin has not created a set of terms and conditions; however, you can request them to create some.";
                return view('terms_and_conditions.show')
                    ->with('termsAndConditions', null)
                    ->with('errorMessage', $errorMessage);
            }
        }

        return view('terms_and_conditions.edit')->with('termsAndConditions', $termsAndConditions);
    }

    /**
     * Update the specified TermsAndConditions in storage.
     *
     * @param  int                            $id
     * @param UpdateTermsAndConditionsRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id, UpdateTermsAndConditionsRequest $request)
    {
        $termsAndConditions = $this->termsAndConditionsRepository->findWithoutFail($id);

        if (empty($termsAndConditions)) {
            Flash::error('Terms And Conditions not found');

            return redirect(route('terms-and-conditions.create'));
        }

        $termsAndConditions = $this->termsAndConditionsRepository->update($request->all(), $id);

        Flash::success('Terms And Conditions updated successfully.');

        return redirect(route('terms-and-conditions'));
    }
}
