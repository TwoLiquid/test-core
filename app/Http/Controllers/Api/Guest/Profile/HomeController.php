<?php

namespace App\Http\Controllers\Api\Guest\Profile;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Profile\Interfaces\HomeControllerInterface;
use App\Http\Requests\Api\Guest\Profile\Home\GetFavoriteVybesRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetSubscribersRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetSubscriptionsRequest;
use App\Http\Requests\Api\Guest\Profile\Home\GetVybesRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Profile\Home\Vybe\VybeTransformer;
use App\Transformers\Api\Guest\Profile\Home\UserFollowerTransformer;
use App\Transformers\Api\Guest\Profile\Home\UserFollowingTransformer;
use App\Transformers\Api\Guest\Profile\Home\UserProfileTransformer;
use App\Transformers\Api\Guest\Profile\Home\Vybe\VybeFormTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers\Api\Guest\Profile
 */
final class HomeController extends BaseController implements HomeControllerInterface
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
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

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
     * @var UserService
     */
    protected UserService $userService;

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
     * HomeController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

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

        /** @var UserService userService */
        $this->userService = new UserService();

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
     * @param string $username
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        string $username
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->getUserProfileByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/home/index.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([], new VybeFormTransformer) +
            $this->transformItem(
                $user,
                new UserProfileTransformer(
                    AuthService::user(),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByFullProfile(
                            new Collection([$user])
                        )
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userVoiceSampleRepository->getByUsers(
                        $this->userService->getByFullProfile(
                            new Collection([$user])
                        )
                    ),
                    $this->userImageRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userVideoRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->vybeImageRepository->getByVybes(
                        $this->vybeService->getByFullProfile(
                            new Collection([$user])
                        )
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $this->vybeService->getByFullProfile(
                            new Collection([$user])
                        )
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByFullProfile(
                            new Collection([$user])
                        )
                    )
                )
            ), trans('validations/api/guest/profile/home/index.result.success')
        );
    }

    /**
     * @param string $username
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getVybes(
        string $username,
        GetVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/home/getVybes.result.error.find')
            );
        }

        /**
         * Getting user vybes
         */
        $vybes = $this->vybeRepository->getByUserPaginated(
            $user,
            $request->input('activity_id'),
            $request->input('vybe_sort_id'),
            $request->input('vybe_types_ids'),
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    AuthService::user(),
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/guest/profile/home/getVybes.result.success')
        );
    }

    /**
     * @param string $username
     * @param GetFavoriteVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getFavoriteVybes(
        string $username,
        GetFavoriteVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/home/getFavoriteVybes.result.error.find')
            );
        }

        /**
         * Getting user favorite vybes
         */
        $vybes = $this->vybeRepository->getFavoritesByUserPaginated(
            $user,
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    AuthService::user(),
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/guest/profile/home/getFavoriteVybes.result.success')
        );
    }

    /**
     * @param string $username
     * @param GetSubscriptionsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getSubscriptions(
        string $username,
        GetSubscriptionsRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->getUserProfileByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/home/getSubscriptions.result.error.find')
            );
        }

        /**
         * Checking search existence
         */
        if ($request->input('search')) {

            /**
             * Getting user subscriptions by search with pagination
             */
            $subscriptions = $this->userRepository->getUserSubscriptionsBySearchPaginated(
                $user,
                $request->input('search'),
                $request->input('page')
            );
        } else {

            /**
             * Getting user subscriptions with pagination
             */
            $subscriptions = $this->userRepository->getUserSubscriptionsPaginated(
                $user,
                $request->input('page')
            );
        }

        return $this->respondWithSuccess(
            $this->transformCollection($subscriptions, new UserFollowingTransformer(
                AuthService::user(),
                $this->userAvatarRepository->getByUsers(
                    new Collection($subscriptions->items())
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    new Collection($subscriptions->items())
                )
            )), trans('validations/api/guest/profile/home/getSubscriptions.result.success')
        );
    }

    /**
     * @param string $username
     * @param GetSubscribersRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getSubscribers(
        string $username,
        GetSubscribersRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->getUserProfileByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/home/getSubscribers.result.error.find')
            );
        }

        /**
         * Checking search existence
         */
        if ($request->input('search')) {

            /**
             * Getting user subscribers by search with pagination
             */
            $subscribers = $this->userRepository->getUserSubscribersBySearchPaginated(
                $user,
                $request->input('search'),
                $request->input('page')
            );
        } else {

            /**
             * Getting user subscribers with pagination
             */
            $subscribers = $this->userRepository->getUserSubscribersPaginated(
                $user,
                $request->input('page')
            );
        }

        return $this->respondWithSuccess(
            $this->transformCollection($subscribers, new UserFollowerTransformer(
                AuthService::user(),
                $this->userAvatarRepository->getByUsers(
                    new Collection($subscribers->items())
                ),
                $this->userVoiceSampleRepository->getByUsers(
                    new Collection($subscribers->items())
                )
            )), trans('validations/api/guest/profile/home/getSubscribers.result.success')
        );
    }
}
