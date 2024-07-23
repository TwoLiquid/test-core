<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeDeletionRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\DeletionRequest\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\Vybe\DeletionRequest\VybeDeletionRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeDeletionRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeDeletionRequestController extends BaseController implements VybeDeletionRequestControllerInterface
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
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * VybeDeletionRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();
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
         * Getting vybe deletion request
         */
        $vybeDeletionRequest = $this->vybeDeletionRequestRepository->findFullById($id);

        /**
         * Checking vybe deletion request existence
         */
        if (!$vybeDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/deletionRequest/show.result.error.find')
            );
        }

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem($vybeDeletionRequest, new VybeDeletionRequestTransformer),
            trans('validations/api/admin/vybe/deletionRequest/show.result.success')
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
         * Getting vybe deletion request
         */
        $vybeDeletionRequest = $this->vybeDeletionRequestRepository->findFullById($id);

        /**
         * Checking vybe deletion request existence
         */
        if (!$vybeDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/deletionRequest/show.result.error.find')
            );
        }

        /**
         * Checking vybe deletion request status
         */
        if (!$vybeDeletionRequest->getRequestStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/deletionRequest/update.result.error.pending')
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
                trans('validations/api/admin/vybe/deletionRequest/update.result.error.find.statusStatus')
            );
        }

        /**
         * Updating vybe deletion request
         */
        $vybeDeletionRequest = $this->vybeDeletionRequestRepository->updateStatus(
            $vybeDeletionRequest,
            $statusStatus
        );

        /**
         * Checking vybe deletion request status
         */
        if ($statusStatus->isAccepted()) {

            /**
             * Updating vybe deletion request
             */
            $vybeDeletionRequest = $this->vybeDeletionRequestRepository->updateRequestStatus(
                $vybeDeletionRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateStatus(
                $vybeDeletionRequest->vybe,
                VybeStatusList::getDeletedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeDeletionAccepted(
                $vybeDeletionRequest->vybe->user,
                $vybeDeletionRequest->vybe
            );
        } else {

            /**
             * Updating vybe deletion request
             */
            $vybeDeletionRequest = $this->vybeDeletionRequestRepository->updateToastMessageText(
                $vybeDeletionRequest,
                $request->input('toast_message_text')
            );

            /**
             * Updating vybe deletion request
             */
            $vybeDeletionRequest = $this->vybeDeletionRequestRepository->updateRequestStatus(
                $vybeDeletionRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeDeletionDeclined(
                $vybeDeletionRequest->vybe->user,
                $vybeDeletionRequest->vybe
            );
        }

        /**
         * Updating processing admin
         */
        $this->vybeDeletionRequestRepository->updateAdmin(
            $vybeDeletionRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($vybeDeletionRequest, new VybeDeletionRequestTransformer),
            trans('validations/api/admin/vybe/deletionRequest/update.result.success')
        );
    }
}
