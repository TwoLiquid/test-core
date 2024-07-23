<?php

namespace App\Http\Controllers\Api\Admin\User\Information;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Information\Interfaces\SubscriberControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Information\Subscription\IndexRequest;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\Information\Following\UserFollowingTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class SubscriberController
 *
 * @package App\Http\Controllers\Api\Admin\User\Information
 */
final class SubscriberController extends BaseController implements SubscriberControllerInterface
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
     * SubscriberController constructor
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
                trans('validations/api/admin/user/information/subscriber/index.result.error.find')
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

        return $this->setPagination($subscriptions)->respondWithSuccess(
            $this->transformCollection($subscriptions, new UserFollowingTransformer(
                $this->userAvatarRepository->getByUsers(
                    new Collection($subscriptions->items())
                )
            )), trans('validations/api/admin/user/information/subscriber/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $subscriberId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $subscriberId
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
                trans('validations/api/admin/user/information/subscriber/destroy.result.error.find.user')
            );
        }

        /**
         * Getting user subscriber
         */
        $subscriber = $this->userRepository->findById(
            $subscriberId
        );

        /**
         * Checking user existence
         */
        if (!$subscriber) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/subscriber/destroy.result.error.find.subscriber')
            );
        }

        /**
         * Checking user subscriber
         */
        if (!$this->userService->isSubscriber(
            $user,
            $subscriber
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/subscriber/destroy.result.error.subscriber')
            );
        }

        /**
         * Detaching subscriber
         */
        $this->userRepository->detachSubscriber(
            $user,
            $subscriber
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/information/subscriber/destroy.result.success')
        );
    }
}
