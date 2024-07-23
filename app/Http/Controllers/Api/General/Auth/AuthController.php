<?php

namespace App\Http\Controllers\Api\General\Auth;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\General\Auth\Interfaces\AuthControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\General\Auth\AttachFavoriteActivitiesRequest;
use App\Http\Requests\Api\General\Auth\DetachFavoriteActivitiesRequest;
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
use App\Services\Notification\EmailNotificationService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Auth\User\AuthUserTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api\General\Auth
 */
final class AuthController extends BaseController implements AuthControllerInterface
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
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

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
     * AuthController constructor
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

        /** @var AuthService authService */
        $this->authService = new AuthService();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

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
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getAuthUser() : JsonResponse
    {
        /**
         * Getting auth user
         */
        $user = $this->userRepository->getAuthUser(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $user,
                new AuthUserTransformer(
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByFullProfile(
                            new Collection([$user])
                        )
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        new Collection([$user])
                    )
                )
            ), trans('validations/api/general/auth/getAuthUser.result.success')
        );
    }

    /**
     * @param int $subscriptionId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachSubscription(
        int $subscriptionId
    ) : JsonResponse
    {
        /**
         * Getting subscription
         */
        $subscription = $this->userRepository->findById($subscriptionId);

        /**
         * Checking subscription existence
         */
        if (!$subscription) {
            return $this->respondWithError(
                trans('validations/api/general/auth/attachSubscription.result.error.find')
            );
        }

        /**
         * Attaching subscription
         */
        $this->userRepository->attachSubscription(
            AuthService::user(),
            $subscription
        );

        /**
         * Send new follower email notification
         */
        $this->emailNotificationService->sendFollowerNew(
            AuthService::user(),
            $subscription
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/attachSubscription.result.success')
        );
    }

    /**
     * @param int $subscriptionId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function detachSubscription(
        int $subscriptionId
    ) : JsonResponse
    {
        /**
         * Getting subscription
         */
        $subscription = $this->userRepository->findById($subscriptionId);

        /**
         * Checking subscription existence
         */
        if (!$subscription) {
            return $this->respondWithError(
                trans('validations/api/general/auth/detachSubscription.result.error.find')
            );
        }

        /**
         * Detaching subscription
         */
        $this->userRepository->detachSubscription(
            AuthService::user(),
            $subscription
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/detachSubscription.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachFavoriteVybe(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $favoriteVybe = $this->vybeRepository->findById($id);

        /**
         * Checking favorite vybe existence
         */
        if (!$favoriteVybe) {
            return $this->respondWithError(
                trans('validations/api/general/auth/attachFavoriteVybe.result.error.find')
            );
        }

        /**
         * Attaching favorite vybe
         */
        $this->userRepository->attachFavoriteVybe(
            AuthService::user(),
            $favoriteVybe
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/attachFavoriteVybe.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function detachFavoriteVybe(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $favoriteVybe = $this->vybeRepository->findById($id);

        /**
         * Checking favorite vybe existence
         */
        if (!$favoriteVybe) {
            return $this->respondWithError(
                trans('validations/api/general/auth/detachFavoriteVybe.result.error.find')
            );
        }

        /**
         * Detaching favorite vybe
         */
        $this->userRepository->detachFavoriteVybe(
            AuthService::user(),
            $favoriteVybe
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/detachFavoriteVybe.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachFavoriteActivity(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $favoriteActivity = $this->activityRepository->findById($id);

        /**
         * Checking favorite activity existence
         */
        if (!$favoriteActivity) {
            return $this->respondWithError(
                trans('validations/api/general/auth/attachFavoriteActivity.result.error.find')
            );
        }

        /**
         * Attaching favorite activity
         */
        $this->userRepository->attachFavoriteActivity(
            AuthService::user(),
            $favoriteActivity
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/attachFavoriteActivity.result.success')
        );
    }

    /**
     * @param AttachFavoriteActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachFavoriteActivities(
        AttachFavoriteActivitiesRequest $request
    ) : JsonResponse
    {
        /**
         * Attaching favorite activities
         */
        $this->userRepository->attachFavoriteActivities(
            AuthService::user(),
            $request->input('ids'),
            $request->input('detaching')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/attachFavoriteActivities.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function detachFavoriteActivity(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting favorite activity
         */
        $favoriteActivity = $this->activityRepository->findById($id);

        /**
         * Checking favorite activity existence
         */
        if (!$favoriteActivity) {
            return $this->respondWithError(
                trans('validations/api/general/auth/detachFavoriteActivity.result.error.find')
            );
        }

        /**
         * Detaching favorite activity
         */
        $this->userRepository->detachFavoriteActivity(
            AuthService::user(),
            $favoriteActivity
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/detachFavoriteActivity.result.success')
        );
    }

    /**
     * @param DetachFavoriteActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function detachFavoriteActivities(
        DetachFavoriteActivitiesRequest $request
    ) : JsonResponse
    {
        /**
         * Detaching favorite activities
         */
        $this->userRepository->detachFavoriteActivities(
            AuthService::user(),
            $request->input('ids')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/detachFavoriteActivities.result.success')
        );
    }

    /**
     * @param int $blockedUserId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachBlockedUser(
        int $blockedUserId
    ) : JsonResponse
    {
        /**
         * Getting blocked user
         */
        $blockedUser = $this->userRepository->findById($blockedUserId);

        /**
         * Checking blocked user existence
         */
        if (!$blockedUser) {
            return $this->respondWithError(
                trans('validations/api/general/auth/attachBlockedUser.result.error.find')
            );
        }

        /**
         * Checking is user blocked
         */
        if ($blockedUserId == AuthService::user()->id) {
            return $this->respondWithError(
                trans('validations/api/general/auth/attachBlockedUser.result.error.yourself')
            );
        }

        /**
         * Attaching blocked user
         */
        $this->userRepository->attachBlockedUser(
            AuthService::user(),
            $blockedUser
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/attachBlockedUser.result.success')
        );
    }

    /**
     * @param int $blockedUserId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function detachBlockedUser(
        int $blockedUserId
    ) : JsonResponse
    {
        /**
         * Getting blocked user
         */
        $blockedUser = $this->userRepository->findById($blockedUserId);

        /**
         * Checking blocked user existence
         */
        if (!$blockedUser) {
            return $this->respondWithError(
                trans('validations/api/general/auth/detachBlockedUser.result.error.find')
            );
        }

        /**
         * Detaching blocked user
         */
        $this->userRepository->detachBlockedUser(
            AuthService::user(),
            $blockedUser
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/detachBlockedUser.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function logout() : JsonResponse
    {
        /**
         * Logout user
         */
        $this->authMicroservice->logout();

        return $this->respondWithSuccess([],
            trans('validations/api/general/auth/logout.result.success')
        );
    }
}
