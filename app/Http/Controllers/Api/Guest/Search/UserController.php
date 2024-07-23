<?php

namespace App\Http\Controllers\Api\Guest\Search;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Search\Interfaces\UserControllerInterface;
use App\Http\Requests\Api\Guest\Search\User\IndexRequest;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\User\UserRepository;
use App\Transformers\Api\Guest\Search\UserSearchTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api\Main\General\User
 */
final class UserController extends BaseController implements UserControllerInterface
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserBackgroundRepository
     */
    protected UserBackgroundRepository $userBackgroundRepository;

    /**
     * @var UserVoiceSampleRepository
     */
    protected UserVoiceSampleRepository $userVoiceSampleRepository;

    /**
     * @var UserImageRepository
     */
    protected UserImageRepository $userImageRepository;

    /**
     * @var UserVideoRepository
     */
    protected UserVideoRepository $userVideoRepository;

    /**
     * UserController constructor
     */
    public function __construct()
    {
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserVideoRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserBackgroundRepository userBackgroundRepository */
        $this->userBackgroundRepository = new UserBackgroundRepository();

        /** @var UserVoiceSampleRepository userVoiceSampleRepository */
        $this->userVoiceSampleRepository = new UserVoiceSampleRepository();

        /** @var UserImageRepository userImageRepository */
        $this->userImageRepository = new UserImageRepository();

        /** @var UserVideoRepository userVideoRepository */
        $this->userVideoRepository = new UserVideoRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking search existence
         */
        if ($request->input('search')) {

            /**
             * Getting users by search with pagination
             */
            $users = $this->userRepository->getAllBySearchPaginated(
                $request->input('search'),
                $request->input('page'),
                $request->input('per_page')
            );
        } else {

            /**
             * Getting users with pagination
             */
            $users = $this->userRepository->getAllPaginated(
                $request->input('page'),
                $request->input('per_page')
            );
        }

        return $this->setPagination($users)->respondWithSuccess(
            $this->transformItem([], new UserSearchTransformer(
                new Collection($users->items()),
                $this->userAvatarRepository->getByUsers(
                    new Collection($users->items())
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    new Collection($users->items())
                )
            ))['user_search'],
            trans('validations/api/guest/search/user/index.result.success')
        );
    }
}
