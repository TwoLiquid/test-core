<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeUnpublishRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\UnpublishRequest\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Transformers\Api\Admin\Vybe\UnpublishRequest\VybeUnpublishRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnpublishRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeUnpublishRequestController extends BaseController implements VybeUnpublishRequestControllerInterface
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
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * VybeUnpublishRequestController constructor
     */
    public function __construct()
    {
        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();
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
         * Getting vybe unpublish request
         */
        $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->findFullById($id);

        /**
         * Checking vybe unpublish request existence
         */
        if (!$vybeUnpublishRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unpublishRequest/show.result.error.find')
            );
        }

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem($vybeUnpublishRequest, new VybeUnpublishRequestTransformer),
            trans('validations/api/admin/vybe/unpublishRequest/show.result.success')
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
         * Getting vybe unpublish request
         */
        $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->findFullById($id);

        /**
         * Checking vybe unpublish request existence
         */
        if (!$vybeUnpublishRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unpublishRequest/show.result.error.find')
            );
        }

        /**
         * Checking vybe unpublish request status
         */
        if (!$vybeUnpublishRequest->getRequestStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/unpublishRequest/update.result.error.pending')
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
                trans('validations/api/admin/vybe/unpublishRequest/update.result.error.find.statusStatus')
            );
        }

        /**
         * Updating vybe unpublish request
         */
        $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->updateStatus(
            $vybeUnpublishRequest,
            $statusStatus
        );

        /**
         * Checking vybe unpublish request status
         */
        if ($statusStatus->isAccepted()) {

            /**
             * Updating vybe unpublish request
             */
            $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->updateRequestStatus(
                $vybeUnpublishRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateStatus(
                $vybeUnpublishRequest->vybe,
                VybeStatusList::getDraftItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeUnpublishAccepted(
                $vybeUnpublishRequest->vybe->user,
                $vybeUnpublishRequest->vybe
            );
        } else {

            /**
             * Updating vybe unpublish request
             */
            $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->updateRequestStatus(
                $vybeUnpublishRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeUnpublishDeclined(
                $vybeUnpublishRequest->vybe->user,
                $vybeUnpublishRequest->vybe
            );
        }

        /**
         * Updating vybe unpublish request
         */
        $this->vybeUnpublishRequestRepository->updateToastMessageText(
            $vybeUnpublishRequest,
            $request->input('toast_message_text')
        );

        /**
         * Updating processing admin
         */
        $this->vybeUnpublishRequestRepository->updateAdmin(
            $vybeUnpublishRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($vybeUnpublishRequest, new VybeUnpublishRequestTransformer),
            trans('validations/api/admin/vybe/unpublishRequest/update.result.success')
        );
    }
}
