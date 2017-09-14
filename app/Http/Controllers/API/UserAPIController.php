<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Transformers\UsersTransformer;

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
        $users = $this->userRepository->all();

        for ($i = 0; $i < count($users); $i++) {
            $users[$i] = (new UsersTransformer)->transform($users[$i], true);
        }

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
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
