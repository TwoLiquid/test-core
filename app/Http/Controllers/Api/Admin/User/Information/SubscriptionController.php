<?php

namespace App\Http\Controllers\Api\Admin\User\Information;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Information\Interfaces\SubscriptionControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Information\Subscription\IndexRequest;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\Information\Following\UserFollowingTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class SubscriptionController
 *
 * @package App\Http\Controllers\Api\Admin\User\Information
 */
final class SubscriptionController extends BaseController implements SubscriptionControllerInterface
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
     * SubscriptionController constructor
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
                trans('validations/api/admin/user/information/subscription/index.result.error.find')
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
                $this->userAvatarRepository->getByUsers(
                    new Collection($subscriptions->items())
                )
            )), trans('validations/api/admin/user/information/subscription/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $subscriptionId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $subscriptionId
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
                trans('validations/api/admin/user/information/subscription/destroy.result.error.find.user')
            );
        }

        /**
         * Getting user subscription
         */
        $subscription = $this->userRepository->findById(
            $subscriptionId
        );

        /**
         * Checking user subscription existence
         */
        if (!$subscription) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/subscription/destroy.result.error.find.subscription')
            );
        }

        /**
         * Checking user subscription
         */
        if (!$this->userService->isSubscription(
            $user,
            $subscription
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/user/information/subscription/destroy.result.error.subscription')
            );
        }

        /**
         * Detaching subscription
         */
        $this->userRepository->detachSubscription(
            $user,
            $subscription
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/information/subscription/destroy.result.success')
        );
    }
}
