<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeUnsuspendRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\UnsuspendRequest\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\Vybe\UnsuspendRequest\VybeUnsuspendRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnsuspendRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeUnsuspendRequestController extends BaseController implements VybeUnsuspendRequestControllerInterface
{
    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * VybeUnsuspendRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting vybe unsuspend request
         */
        $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->findFullById($id);

        /**
         * Checking vybe unsuspend request existence
         */
        if (!$vybeUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unsuspendRequest/show.result.error.find')
            );
        }

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem($vybeUnsuspendRequest, new VybeUnsuspendRequestTransformer),
            trans('validations/api/admin/vybe/unsuspendRequest/show.result.success')
        );
    }

    /**
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe unsuspend request
         */
        $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->findFullById($id);

        /**
         * Checking vybe unsuspend request existence
         */
        if (!$vybeUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unsuspendRequest/show.result.error.find')
            );
        }

        /**
         * Checking vybe unsuspend request status
         */
        if (!$vybeUnsuspendRequest->getRequestStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unsuspendRequest/update.result.error.pending')
            );
        }

        /**
         * Getting status status
         */
        $statusStatus = RequestFieldStatusList::getItem(
            $request->input('status_status_id')
        );

        /**
         * Checking status existence
         */
        if (!$statusStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unsuspendRequest/update.result.error.find.statusStatus')
            );
        }

        /**
         * Updating vybe unsuspend request
         */
        $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->updateStatus(
            $vybeUnsuspendRequest,
            $statusStatus
        );

        /**
         * Checking vybe unsuspend request status
         */
        if ($statusStatus->isAccepted()) {

            /**
             * Updating vybe unsuspend request
             */
            $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->updateRequestStatus(
                $vybeUnsuspendRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateStatus(
                $vybeUnsuspendRequest->vybe,
                VybeStatusList::getDraftItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeUnsuspensionAccepted(
                $vybeUnsuspendRequest->vybe->user,
                $vybeUnsuspendRequest->vybe
            );
        } else {

            /**
             * Updating vybe unsuspend request
             */
            $this->vybeUnsuspendRequestRepository->updateToastMessageText(
                $vybeUnsuspendRequest,
                $request->input('toast_message_text')
            );

            /**
             * Updating vybe unsuspend request
             */
            $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->updateRequestStatus(
                $vybeUnsuspendRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeUnsuspensionDeclined(
                $vybeUnsuspendRequest->vybe->user,
                $vybeUnsuspendRequest->vybe
            );
        }

        /**
         * Updating processing admin
         */
        $this->vybeUnsuspendRequestRepository->updateAdmin(
            $vybeUnsuspendRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($vybeUnsuspendRequest, new VybeUnsuspendRequestTransformer),
            trans('validations/api/admin/vybe/unsuspendRequest/update.result.success')
        );
    }
}
