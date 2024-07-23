<?php

namespace App\Http\Controllers\Api\Admin\User\IdVerification;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces\UserIdVerificationRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\IdVerification\Request\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\UserIdVerificationImageRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\User\UserIdVerificationRequestService;
use App\Transformers\Api\Admin\User\IdVerification\UserIdVerificationRequestTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class UserIdVerificationRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\IdVerification
 */
final class UserIdVerificationRequestController extends BaseController implements UserIdVerificationRequestControllerInterface
{
    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserIdVerificationRequestService
     */
    protected UserIdVerificationRequestService $userIdVerificationRequestService;

    /**
     * @var UserIdVerificationImageRepository
     */
    protected UserIdVerificationImageRepository $userIdVerificationImageRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserIdVerificationRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserIdVerificationRequestService userIdVerificationRequestService */
        $this->userIdVerificationRequestService = new UserIdVerificationRequestService();

        /** @var UserIdVerificationImageRepository userIdVerificationImageRepository */
        $this->userIdVerificationImageRepository = new UserIdVerificationImageRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
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
                trans('validations/api/admin/user/idVerification/request/update.result.error.find.user')
            );
        }

        /**
         * Getting pending user id verification
         */
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking user id verification request existence
         */
        if (!$userIdVerificationRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/idVerification/request/update.result.error.find.idVerificationRequest')
            );
        }

        /**
         * Getting user id verification data
         */
        $idVerificationStatusStatusListItem = $userIdVerificationRequest->getVerificationStatusStatus();
        if ($request->input('verification_status_status_id')) {
            $idVerificationStatusStatusListItem = RequestFieldStatusList::getItem(
                $request->input('verification_status_status_id')
            );
        }

        /**
         * Update user id verification
         */
        $this->userIdVerificationRequestRepository->update(
            $userIdVerificationRequest,
            $idVerificationStatusStatusListItem,
            $request->input('toast_message_text')
        );

        /**
         * Check user id verification acceptance
         */
        if ($idVerificationStatusStatusListItem->isAccepted()) {

            /**
             * Update user id verification status
             */
            $this->userIdVerificationRequestRepository->updateRequestStatus(
                $userIdVerificationRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Update id verification toast message type
             */
            $this->userIdVerificationRequestRepository->updateToastMessageType(
                $userIdVerificationRequest,
                ToastMessageTypeList::getAcceptedItem()
            );

            /**
             * Update user id verification
             */
            $this->userRepository->updateVerification(
                $userIdVerificationRequest->user,
                UserIdVerificationStatusList::getVerified(),
                null
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendIdVerificationAccepted(
                $user
            );
        } else {

            /**
             * Declining user id verification document in micro service
             */
            $this->mediaMicroservice->declineUserIdVerificationImage(
                $userIdVerificationRequest
            );

            /**
             * Update user id verification status
             */
            $this->userIdVerificationRequestRepository->updateRequestStatus(
                $userIdVerificationRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Update user id verification toast message type
             */
            $this->userIdVerificationRequestRepository->updateToastMessageType(
                $userIdVerificationRequest,
                ToastMessageTypeList::getDeclinedItem()
            );

            /**
             * Update user id verification
             */
            $this->userRepository->updateVerification(
                $userIdVerificationRequest->user,
                UserIdVerificationStatusList::getUnverified(),
                $request->input('verification_suspended')
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendIdVerificationDeclined(
                $user
            );
        }

        /**
         * Updating processing admin
         */
        $this->userIdVerificationRequestRepository->updateAdmin(
            $userIdVerificationRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($userIdVerificationRequest, new UserIdVerificationRequestTransformer(
                $this->userIdVerificationImageRepository->getByRequests(
                    new Collection([$userIdVerificationRequest])
                )
            )), trans('validations/api/admin/user/idVerification/request/update.result.success')
        );
    }
}
