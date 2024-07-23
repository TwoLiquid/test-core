<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Deletion;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Request\Deletion\Interfaces\UserDeletionRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Request\Deletion\UpdateRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\User\Request\Deletion\UserDeletionRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserDeletionRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Deletion
 */
final class UserDeletionRequestController extends BaseController implements UserDeletionRequestControllerInterface
{
    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UserDeletionRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
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
                trans('validations/api/admin/user/request/deletion/index.result.error.find.user')
            );
        }

        /**
         * Getting pending user deletion request
         */
        $userDeletionRequest = $this->userDeletionRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user deletion request existence
         */
        if (!$userDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/deletion/index.result.error.find.deletionRequest')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userDeletionRequest, new UserDeletionRequestTransformer),
            trans('validations/api/admin/user/request/deletion/show.result.success')
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
                trans('validations/api/admin/user/request/deletion/update.result.error.find.user')
            );
        }

        /**
         * Getting pending user deletion request
         */
        $userDeletionRequest = $this->userDeletionRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user deletion request existence
         */
        if (!$userDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/deletion/update.result.error.find.deletionRequest')
            );
        }

        /**
         * Getting account status
         */
        $accountStatusStatus = RequestFieldStatusList::getItem(
            $request->input('account_status_status_id')
        );

        /**
         * Updating user deletion request
         */
        $this->userDeletionRequestRepository->update(
            $userDeletionRequest,
            $accountStatusStatus,
            $request->input('toast_message_text')
        );

        /**
         * Checking user deletion request acceptance
         */
        if ($accountStatusStatus->isAccepted()) {

            /**
             * Updating user deletion request status
             */
            $this->userDeletionRequestRepository->updateRequestStatus(
                $userDeletionRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating user deletion request toast message type
             */
            $this->userDeletionRequestRepository->updateToastMessageType(
                $userDeletionRequest,
                ToastMessageTypeList::getAcceptedItem()
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $user,
                AccountStatusList::getDeleted()
            );

            /**
             * Updating user deleted at
             */
            $this->userRepository->setTemporaryDeletedAt(
                $user
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendDeletionRequestAccepted(
                $user
            );
        } else {

            /**
             * Updating user deletion request status
             */
            $this->userDeletionRequestRepository->updateRequestStatus(
                $userDeletionRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Updating user deletion request toast message type
             */
            $this->userDeletionRequestRepository->updateToastMessageType(
                $userDeletionRequest,
                ToastMessageTypeList::getDeclinedItem()
            );

            /**
             * Getting account status data
             */
            $accountStatusListItem = AccountStatusList::getItem(
                $userDeletionRequest->previous_account_status_id
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $userDeletionRequest->user,
                $accountStatusListItem
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendDeletionRequestDeclined(
                $user
            );
        }

        /**
         * Updating processing admin
         */
        $this->userDeletionRequestRepository->updateAdmin(
            $userDeletionRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($userDeletionRequest, new UserDeletionRequestTransformer),
            trans('validations/api/admin/user/request/deletion/update.result.success')
        );
    }
}
