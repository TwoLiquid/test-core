<?php

namespace App\Http\Controllers\Api\Admin\User\Information;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Information\Interfaces\UserBlockControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Information\Block\IndexRequest;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\Information\Block\UserTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserBlockController
 *
 * @package App\Http\Controllers\Api\Admin\User\Information
 */
final class UserBlockController extends BaseController implements UserBlockControllerInterface
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * UserBlockController constructor
     */
    public function __construct()
    {
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/block/index.result.error.find')
            );
        }

        /**
         * Checking search existence
         */
        if ($request->input('search')) {

            /**
             * Getting blocked users by search with pagination
             */
            $blockedUsers = $this->userRepository->getBlockedUsersBySearchPaginated(
                $user,
                $request->input('search'),
                $request->input('page'),
                $request->input('per_page')
            );
        } else {

            /**
             * Getting blocked users with pagination
             */
            $blockedUsers = $this->userRepository->getBlockedUsersPaginated(
                $user,
                $request->input('page'),
                $request->input('per_page')
            );
        }

        return $this->setPagination($blockedUsers)->respondWithSuccess(
            $this->transformCollection($blockedUsers, new UserTransformer(
                $this->userAvatarRepository->getByUsers(
                    new Collection($blockedUsers->items())
                )
            )), trans('validations/api/admin/user/information/block/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $blockedUserId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $blockedUserId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/block/destroy.result.error.find.user')
            );
        }

        /**
         * Getting blocked user
         */
        $blockedUser = $this->userRepository->findById(
            $blockedUserId
        );

        /**
         * Checking blocked user existence
         */
        if (!$blockedUser) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/block/destroy.result.error.find.blockedUser')
            );
        }

        /**
         * Checking user blocked
         */
        if (!$this->userService->isBlocked(
            $user,
            $blockedUser
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/block/destroy.result.error.blockedUser')
            );
        }

        /**
         * Detaching blocked user
         */
        $this->userRepository->detachBlockedUser(
            $user,
            $blockedUser
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/information/block/destroy.result.success')
        );
    }
}
