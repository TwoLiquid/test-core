<?php

namespace App\Http\Controllers\Api\Guest\Search;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Search\Interfaces\SearchControllerInterface;
use App\Http\Requests\Api\Guest\Search\GlobalSearchRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Search\GlobalSearchTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers\Api\Guest\Search
 */
final class SearchController extends BaseController implements SearchControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

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
     * @var UserVoiceSampleRepository
     */
    protected UserVoiceSampleRepository $userVoiceSampleRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

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
     * SearchController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserVoiceSampleRepository userVoiceSampleRepository */
        $this->userVoiceSampleRepository = new UserVoiceSampleRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param GlobalSearchRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function globalSearch(
        GlobalSearchRequest $request
    ) : JsonResponse
    {
        /**
         * Checking search existence
         */
        if (!$request->input('search')) {
            return $this->respondWithError(
                trans('validations/api/guest/search/globalSearch.result.error.search')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getWithGlobalSearchByLimit(
            $request->input('search'),
            $request->input('limit')
        );

        /**
         * Getting users
         */
        $users = $this->userRepository->getWithGlobalSearchByLimit(
            $request->input('search'),
            $request->input('limit')
        );

        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getWithGlobalSearchByLimit(
            $request->input('search'),
            $request->input('limit')
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new GlobalSearchTransformer(
                AuthService::user(),
                $activities,
                $users,
                $vybes,
                $this->userAvatarRepository->getByUsers(
                    $users
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    $users
                ),
                $this->vybeImageRepository->getByVybes(
                    $vybes
                ),
                $this->vybeVideoRepository->getByVybes(
                    $vybes
                ),
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByVybes(
                        $vybes
                    )
                )
            ))['global_search'],
            trans('validations/api/guest/search/globalSearch.result.success')
        );
    }
}
