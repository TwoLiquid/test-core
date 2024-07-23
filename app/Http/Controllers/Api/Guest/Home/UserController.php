<?php

namespace App\Http\Controllers\Api\Guest\Home;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Home\Interfaces\UserControllerInterface;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\User\UserRepository;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Home\User\UserTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api\Guest\Home
 */
class UserController extends BaseController implements UserControllerInterface
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
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

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

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting users with vybes
         */
        $users = $this->userRepository->getTopCreators();

        return $this->respondWithSuccess(
            $this->transformCollection(
                $users,
                new UserTransformer(
                    $this->userAvatarRepository->getByUsers(
                        $users
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        $users
                    ),
                    $this->userVoiceSampleRepository->getByUsers(
                        $users
                    ),
                    $this->userImageRepository->getByUsers(
                        $users
                    ),
                    $this->userVideoRepository->getByUsers(
                        $users
                    ),
                    $this->vybeImageRepository->getByVybes(
                        $this->vybeService->getByUsers(
                            $users
                        )
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $this->vybeService->getByUsers(
                            $users
                        )
                    )
                )
            ), trans('validations/api/guest/home/user/index.result.success')
        );
    }
}
