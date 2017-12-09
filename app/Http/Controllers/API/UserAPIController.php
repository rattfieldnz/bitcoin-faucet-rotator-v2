<?php

namespace App\Http\Controllers\API;

use App\Helpers\Functions\Users;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Transformers\UsersTransformer;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class UserController
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    private $userRepository;

    /**
     * UserAPIController constructor.
     *
     * @param \App\Repositories\UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Retrieve all users as JSON.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));

        $systemUsers = (Auth::check() && Auth::user()->isAnAdmin()) ?
            $this->userRepository->withTrashed()->get() :
            $this->userRepository->findWhere(['deleted_at' => null])->all();

        $users = new Collection();
        for ($i = 0; $i < count($systemUsers); $i++) {
            $data = [
                'user_name' => [
                    'display' => route('users.show', ['slug' => $systemUsers[$i]->slug]),
                    'original' => $systemUsers[$i]->user_name,
                ],
                'faucets' => [
                    'display' => route('users.faucets', ['slug' => $systemUsers[$i]->slug]),
                    'original' => 'View ' . $systemUsers[$i]->user_name . '\'s faucets'
                ],
                'no_of_faucets' => count($systemUsers[$i]->faucets()->wherePivot('referral_code', '!=', null)->get()),
                'payment_processors' => [
                    'display' => route('users.payment-processors', ['userSlug' => $systemUsers[$i]->slug]),
                    'original' => 'View ' . $systemUsers[$i]->user_name .'\'s faucets grouped by payment processors'
                ]
            ];

            if (Auth::check() && Auth::user()->isAnAdmin()) {
                $data['id'] = intval($systemUsers[$i]->id);
                $data['role'] = $systemUsers[$i]->roles()->first()->name;
                $data['is_admin'] = [
                    'display' => $systemUsers[$i]->isAnAdmin() == true ? 'Yes' : 'No',
                    'original' => $systemUsers[$i]->isAnAdmin()
                ];
                $data['is_deleted'] = [
                    'display' => $systemUsers[$i]->isDeleted() == true ? "Yes" : "No",
                    'original' => $systemUsers[$i]->isDeleted()
                ];
                $data['actions'] = '';
                $data['actions'] .= Users::htmlEditButton($systemUsers[$i]);

                if (!$systemUsers[$i]->isAnAdmin()) {
                    if ($systemUsers[$i]->isDeleted()) {
                        $data['actions'] .= Users::deletePermanentlyForm($systemUsers[$i]);
                        $data['actions'] .= Users::restoreForm($systemUsers[$i]);
                    }

                    $data['actions'] .= Users::softDeleteForm($systemUsers[$i]);
                }
            }

            $users->push($data);
        }

        return Datatables::of($users)->rawColumns(['actions'])->make(true);
    }

    public function show($slug)
    {
        $user = $this->userRepository->findWhere(['slug' => $slug])->first();

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = (new UsersTransformer)->transform($user, true);

        return $this->sendResponse($user, 'User retrieved successfully');
    }
}
