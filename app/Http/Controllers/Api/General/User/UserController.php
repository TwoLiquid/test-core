<?php

namespace App\Http\Controllers\Api\General\User;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\User\Interfaces\UserControllerInterface;
use App\Http\Requests\Api\General\User\GetUserSubscribersRequest;
use App\Http\Requests\Api\General\User\GetUserSubscriptionsRequest;
use App\Http\Requests\Api\General\User\IndexRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\UserBackgroundRepository;
use App\Repositories\Media\UserImageRepository;
use App\Repositories\Media\UserVideoRepository;
use App\Repositories\Media\UserVoiceSampleRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Transformers\Api\General\User\User\UserFollowerTransformer;
use App\Transformers\Api\General\User\User\UserFollowingTransformer;
use App\Transformers\Api\General\User\User\UserTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Api\General\User
 */
final class UserController extends BaseController implements UserControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

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
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

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
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search existence
             */
            if ($request->input('search')) {

                /**
                 * Getting users by search with pagination
                 */
                $users = $this->userRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page')
                );
            } else {

                /**
                 * Getting users with pagination
                 */
                $users = $this->userRepository->getAllPaginated(
                    $request->input('page')
                );
            }

            return $this->setPagination($users)->respondWithSuccess(
                $this->transformCollection(
                    $users,
                    new UserTransformer(
                        AuthService::user(),
                        $this->userAvatarRepository->getByUsers(
                            new Collection($users->items())
                        ),
                        $this->userBackgroundRepository->getByUsers(
                            new Collection($users->items())
                        ),
                        $this->userVoiceSampleRepository->getByUsers(
                            new Collection($users->items())
                        ),
                        $this->userImageRepository->getByUsers(
                            new Collection($users->items())
                        ),
                        $this->userVideoRepository->getByUsers(
                            new Collection($users->items())
                        )
                    )
                ), trans('validations/api/general/user/index.result.success')
            );
        }

        /**
         * Checking search existence
         */
        if ($request->input('search')) {

            /**
             * Getting users by search
             */
            $users = $this->userRepository->getAllBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting user
             */
            $users = $this->userRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection(
                $users,
                new UserTransformer(
                    AuthService::user(),
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
                    )
                )
            ), trans('validations/api/general/user/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
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
                trans('validations/api/general/user/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $user,
                new UserTransformer(
                    AuthService::user(),
                    $this->userAvatarRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userVoiceSampleRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userImageRepository->getByUsers(
                        new Collection([$user])
                    ),
                    $this->userVideoRepository->getByUsers(
                        new Collection([$user])
                    )
                ))
            , trans('validations/api/general/user/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetUserSubscriptionsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getUserSubscriptions(
        int $id,
        GetUserSubscriptionsRequest $request
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
                trans('validations/api/general/user/getUserSubscriptions.result.error.find')
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
            $this->transformCollection(
                $subscriptions,
                new UserFollowingTransformer(
                    AuthService::user(),
                    $this->userAvatarRepository->getByUsers(
                        new Collection($subscriptions->items())
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        new Collection($subscriptions->items())
                    ),
                    $this->userVoiceSampleRepository->getByUsers(
                        new Collection($subscriptions->items())
                    ),
                    $this->userImageRepository->getByUsers(
                        new Collection($subscriptions->items())
                    ),
                    $this->userVideoRepository->getByUsers(
                        new Collection($subscriptions->items())
                    )
                )
            ), trans('validations/api/general/user/getUserSubscriptions.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetUserSubscribersRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getUserSubscribers(
        int $id,
        GetUserSubscribersRequest $request
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
                trans('validations/api/general/user/getUserSubscribers.result.error.find')
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

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformCollection(
                $subscribers,
                new UserFollowerTransformer(
                    AuthService::user(),
                    $this->userAvatarRepository->getByUsers(
                        new Collection($subscribers->items())
                    ),
                    $this->userBackgroundRepository->getByUsers(
                        new Collection($subscribers->items())
                    ),
                    $this->userVoiceSampleRepository->getByUsers(
                        new Collection($subscribers->items())
                    ),
                    $this->userImageRepository->getByUsers(
                        new Collection($subscribers->items())
                    ),
                    $this->userVideoRepository->getByUsers(
                        new Collection($subscribers->items())
                    )
                )
            ), trans('validations/api/general/user/getUserSubscribers.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function storeVisit(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting visited user
         */
        $visitedUser = $this->userRepository->findById($id);

        /**
         * Checking visited user existence
         */
        if (!$visitedUser) {
            return $this->respondWithError(
                trans('validations/api/general/user/storeVisit.result.error.find')
            );
        }

        /**
         * Creating a user visit
         */
        $this->userRepository->storeVisit(
            AuthService::user(),
            $visitedUser
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/user/storeVisit.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
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
                trans('validations/api/general/user/destroy.result.error.find')
            );
        }

        /**
         * Deleting user
         */
        if (!$this->userRepository->delete($user)) {
            return $this->respondWithError(
                trans('validations/api/general/user/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/general/user/destroy.result.success')
        );
    }
}
