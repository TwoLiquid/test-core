<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Unsuspend;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Request\Unsuspend\Interfaces\UserUnsuspendRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Request\Deletion\UpdateRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\User\Request\Unsuspend\UserUnsuspendRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserUnsuspendRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Unsuspend
 */
final class UserUnsuspendRequestController extends BaseController implements UserUnsuspendRequestControllerInterface
{
    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserUnsuspendRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
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
                trans('validations/api/admin/user/request/unsuspend/index.result.error.find.user')
            );
        }

        /**
         * Getting pending unsuspended request
         */
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user unsuspend request existence
         */
        if (!$userUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/unsuspend/index.result.error.find.userUnsuspendRequest')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userUnsuspendRequest, new UserUnsuspendRequestTransformer),
            trans('validations/api/admin/user/request/unsuspend/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
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
                trans('validations/api/admin/user/request/unsuspend/update.result.error.find.user')
            );
        }

        /**
         * Getting pending user unsuspend request
         */
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user unsuspend request existence
         */
        if (!$userUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/unsuspend/update.result.error.find.userUnsuspendRequest')
            );
        }

        /**
         * Getting account status
         */
        $accountStatusStatus = RequestFieldStatusList::getItem(
            $request->input('account_status_status_id')
        );

        /**
         * Updating user unsuspend request
         */
        $this->userUnsuspendRequestRepository->update(
            $userUnsuspendRequest,
            $accountStatusStatus,
            $request->input('toast_message_text')
        );

        /**
         * Checking user unsuspend request acceptance
         */
        if ($accountStatusStatus->isAccepted()) {

            /**
             * Updating user unsuspend request status
             */
            $this->userUnsuspendRequestRepository->updateRequestStatus(
                $userUnsuspendRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating user unsuspend request toast message type
             */
            $this->userUnsuspendRequestRepository->updateToastMessageType(
                $userUnsuspendRequest,
                ToastMessageTypeList::getAcceptedItem()
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $userUnsuspendRequest->user,
                $userUnsuspendRequest->getAccountStatus()
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendUnsuspensionRequestAccepted(
                $user
            );
        } else {

            /**
             * Updating user unsuspend request status
             */
            $this->userUnsuspendRequestRepository->updateRequestStatus(
                $userUnsuspendRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Updating user unsuspend request toast message type
             */
            $this->userUnsuspendRequestRepository->updateToastMessageType(
                $userUnsuspendRequest,
                ToastMessageTypeList::getDeclinedItem()
            );

            /**
             * Getting account status data
             */
            $accountStatusListItem = AccountStatusList::getItem(
                $userUnsuspendRequest->previous_account_status_id
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $userUnsuspendRequest->user,
                $accountStatusListItem
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendUnsuspensionRequestDeclined(
                $user
            );
        }

        /**
         * Updating processing admin
         */
        $this->userUnsuspendRequestRepository->updateAdmin(
            $userUnsuspendRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($userUnsuspendRequest, new UserUnsuspendRequestTransformer),
            trans('validations/api/admin/user/request/unsuspend/update.result.success')
        );
    }
}
