<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Deactivation;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Request\Deactivation\Interfaces\UserDeactivationRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Request\Deactivation\UpdateRequest;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\User\Request\Deactivation\UserDeactivationRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserDeactivationRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Deactivation
 */
final class UserDeactivationRequestController extends BaseController implements UserDeactivationRequestControllerInterface
{
    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserDeactivationRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

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
                trans('validations/api/admin/user/request/deactivation/index.result.error.find.user')
            );
        }

        /**
         * Getting pending user deactivation request
         */
        $userDeactivationRequest = $this->userDeactivationRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user deactivation request existence
         */
        if (!$userDeactivationRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/deactivation/index.result.error.find.deactivationRequest')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($userDeactivationRequest, new UserDeactivationRequestTransformer),
            trans('validations/api/admin/user/request/deactivation/index.result.success')
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
                trans('validations/api/admin/user/request/deactivation/update.result.error.find.user')
            );
        }

        /**
         * Getting pending user deactivation request
         */
        $userDeactivationRequest = $this->userDeactivationRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user deactivation request existence
         */
        if (!$userDeactivationRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/request/deactivation/update.result.error.find.deactivationRequest')
            );
        }

        /**
         * Getting user deactivation request data
         */
        $accountStatusStatus = RequestFieldStatusList::getItem(
            $request->input('account_status_status_id')
        );

        /**
         * Updating user deactivation request
         */
        $this->userDeactivationRequestRepository->update(
            $userDeactivationRequest,
            $accountStatusStatus,
            $request->input('toast_message_text')
        );

        /**
         * Checking user deactivation request acceptance
         */
        if ($accountStatusStatus->isAccepted()) {

            /**
             * Updating user deactivation request status
             */
            $this->userDeactivationRequestRepository->updateRequestStatus(
                $userDeactivationRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating user deactivation request toast message type
             */
            $this->userDeactivationRequestRepository->updateToastMessageType(
                $userDeactivationRequest,
                ToastMessageTypeList::getAcceptedItem()
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $userDeactivationRequest->user,
                $userDeactivationRequest->getAccountStatus()
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendDeactivationRequestAccepted(
                $user
            );
        } else {

            /**
             * Updating user deactivation request status
             */
            $this->userDeactivationRequestRepository->updateRequestStatus(
                $userDeactivationRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Updating user deactivation request toast message type
             */
            $this->userDeactivationRequestRepository->updateToastMessageType(
                $userDeactivationRequest,
                ToastMessageTypeList::getDeclinedItem()
            );

            /**
             * Getting account status data
             */
            $accountStatusListItem = AccountStatusList::getItem(
                $userDeactivationRequest->previous_account_status_id
            );

            /**
             * Updating user account status
             */
            $this->userRepository->updateAccountStatus(
                $userDeactivationRequest->user,
                $accountStatusListItem
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendDeactivationRequestDeclined(
                $user
            );
        }

        /**
         * Updating processing admin
         */
        $this->userDeactivationRequestRepository->updateAdmin(
            $userDeactivationRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($userDeactivationRequest, new UserDeactivationRequestTransformer),
            trans('validations/api/admin/user/request/deactivation/update.result.success')
        );
    }
}
