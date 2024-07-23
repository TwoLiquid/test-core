<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeChangeRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\ChangeRequest\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Microservices\Media\Responses\VybeVideoCollectionResponse;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Vybe\VybeChangeRequestService;
use App\Services\Vybe\VybeService;
use App\Services\Vybe\VybeVersionService;
use App\Transformers\Api\Admin\Vybe\ChangeRequest\VybeChangeRequestTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class VybeChangeRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeChangeRequestController extends BaseController implements VybeChangeRequestControllerInterface
{
    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeVersionService
     */
    protected VybeVersionService $vybeVersionService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeChangeRequestController constructor
     */
    public function __construct()
    {
        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeVersionService vybeVersionService */
        $this->vybeVersionService = new VybeVersionService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
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
         * Getting vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->findFullById($id);

        /**
         * Checking vybe change request existence
         */
        if (!$vybeChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/request/vybe/changeRequest/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybeChangeRequest,
                new VybeChangeRequestTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybeChangeRequest])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybeChangeRequest])
                    )
                )
            ), trans('validations/api/admin/vybe/changeRequest/show.result.success')
        );
    }

    /**
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     * @throws ValidationException
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->findFullById($id);

        /**
         * Checking vybe change request existence
         */
        if (!$vybeChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.vybeChangeRequest')
            );
        }

        /**
         * Checking vybe change request status
         */
        if (!$vybeChangeRequest->getRequestStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.pending')
            );
        }

        /**
         * Getting a probable first CSAU suggestion
         */
        $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Getting a probable first device suggestion
         */
        $deviceSuggestion = $this->deviceSuggestionRepository->findForVybeChangeRequest(
            $vybeChangeRequest
        );

        /**
         * Getting title status
         */
        $titleStatus = RequestFieldStatusList::getItem(
            $request->input('title_status_id')
        );

        /**
         * Checking title status existence if required
         */
        if ($vybeChangeRequest->getTitleStatus() && !$titleStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.titleStatus')
            );
        }

        /**
         * Getting category status
         */
        $categoryStatus = RequestFieldStatusList::getItem(
            $request->input('category_status_id')
        );

        /**
         * Checking category status existence
         */
        if ($vybeChangeRequest->getCategoryStatus()) {

            /**
             * Checking category status existence
             */
            if ($categoryStatus) {

                /**
                 * Checking CSAU existence
                 */
                if ($csauSuggestion) {

                    /**
                     * Checking CSAU suggestion category status
                     */
                    if ($csauSuggestion->getCategoryStatus() &&
                        $csauSuggestion->getCategoryStatus()->isPending() &&
                        $categoryStatus->isAccepted()
                    ) {
                        return $this->respondWithError(
                            trans('validations/api/admin/vybe/changeRequest/update.result.error.csau.category')
                        );
                    }
                }
            } else {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/changeRequest/update.result.error.find.categoryStatus')
                );
            }
        }

        /**
         * Getting subcategory status
         */
        $subcategoryStatus = RequestFieldStatusList::getItem(
            $request->input('subcategory_status_id')
        );

        /**
         * Checking subcategory status existence
         */
        if ($vybeChangeRequest->getSubcategoryStatus()) {

            /**
             * Checking subcategory status existence
             */
            if ($subcategoryStatus) {

                /**
                 * Checking CSAU existence
                 */
                if ($csauSuggestion) {

                    /**
                     * Checking CSAU suggestion subcategory status
                     */
                    if ($csauSuggestion->getSubcategoryStatus() &&
                        $csauSuggestion->getSubcategoryStatus()->isPending() &&
                        $subcategoryStatus->isAccepted()
                    ) {
                        return $this->respondWithError(
                            trans('validations/api/admin/vybe/changeRequest/update.result.error.csau.subcategory')
                        );
                    }
                }
            } else {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/changeRequest/update.result.error.find.subcategoryStatus')
                );
            }
        }

        /**
         * Getting devices status
         */
        $devicesStatus = RequestFieldStatusList::getItem(
            $request->input('devices_status_id')
        );

        /**
         * Checking device status existence
         */
        if ($vybeChangeRequest->getDevicesStatus()) {

            /**
             * Checking device status existence
             */
            if ($devicesStatus) {

                /**
                 * Checking CSAU device suggestion existence
                 */
                if ($deviceSuggestion) {

                    /**
                     * Checking CSAU suggestion devices status
                     */
                    if ($deviceSuggestion->getStatus() &&
                        $deviceSuggestion->getStatus()->isPending() &&
                        $devicesStatus->isAccepted()
                    ) {
                        return $this->respondWithError(
                            trans('validations/api/admin/vybe/changeRequest/update.result.error.csau.devices')
                        );
                    }
                }
            } else {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/changeRequest/update.result.error.find.devicesStatus')
                );
            }
        }

        /**
         * Getting activity status
         */
        $activityStatus = RequestFieldStatusList::getItem(
            $request->input('activity_status_id')
        );

        /**
         * Checking activity status existence
         */
        if ($vybeChangeRequest->getActivityStatus()) {

            /**
             * Checking activity status existence
             */
            if ($activityStatus) {

                /**
                 * Checking CSAU existence
                 */
                if ($csauSuggestion) {

                    /**
                     * Checking CSAU suggestion activity status
                     */
                    if ($csauSuggestion->getActivityStatus() &&
                        $csauSuggestion->getActivityStatus()->isPending() &&
                        $activityStatus->isAccepted()
                    ) {
                        return $this->respondWithError(
                            trans('validations/api/admin/vybe/changeRequest/update.result.error.csau.activity')
                        );
                    }
                }
            } else {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/changeRequest/update.result.error.find.activityStatus')
                );
            }
        }

        /**
         * Getting period status
         */
        $periodStatus = RequestFieldStatusList::getItem(
            $request->input('period_status_id')
        );

        /**
         * Checking period status existence if required
         */
        if ($vybeChangeRequest->getPeriodStatus() && !$periodStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.periodStatus')
            );
        }

        /**
         * Getting user count status
         */
        $userCountStatus = RequestFieldStatusList::getItem(
            $request->input('user_count_status_id')
        );

        /**
         * Checking user count status existence if required
         */
        if ($vybeChangeRequest->getUserCountStatus() && !$userCountStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.userCountStatus')
            );
        }

        /**
         * Preparing order advance variable
         */
        $orderAdvanceStatus = null;

        /**
         * Getting vybe type
         */
        $vybeType = $this->vybeService->getVybeTypeByParameters(
            $vybeChangeRequest->getPeriod() ?
                $vybeChangeRequest->getPeriod() :
                $vybeChangeRequest->vybe->getPeriod(),
            $vybeChangeRequest->user_count ?
                $vybeChangeRequest->user_count :
                $vybeChangeRequest->vybe->user_count
        );

        /**
         * Checking vybe change request type
         */
        if (!$vybeType->isEvent()) {

            /**
             * Getting order advance status
             */
            $orderAdvanceStatus = RequestFieldStatusList::getItem(
                $request->input('order_advance_status_id')
            );

            /**
             * Checking order advance status existence if required
             */
            if ($vybeChangeRequest->getOrderAdvanceStatus() && !$orderAdvanceStatus) {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/changeRequest/update.result.error.find.orderAdvanceStatus')
                );
            }
        }

        /**
         * Getting access status
         */
        $accessStatus = RequestFieldStatusList::getItem(
            $request->input('access_status_id')
        );

        /**
         * Checking access status existence if required
         */
        if ($vybeChangeRequest->getAccessStatus() && !$accessStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.accessStatus')
            );
        }

        /**
         * Getting showcase status
         */
        $showcaseStatus = RequestFieldStatusList::getItem(
            $request->input('showcase_status_id')
        );

        /**
         * Checking showcase status existence if required
         */
        if ($vybeChangeRequest->getShowcaseStatus() && !$showcaseStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.showcaseStatus')
            );
        }

        /**
         * Getting order accept status
         */
        $orderAcceptStatus = RequestFieldStatusList::getItem(
            $request->input('order_accept_status_id')
        );

        /**
         * Checking order accept status existence if required
         */
        if ($vybeChangeRequest->getOrderAcceptStatus() && !$orderAcceptStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.orderAcceptStatus')
            );
        }

        /**
         * Getting age limit
         */
        $vybeAgeLimit = VybeAgeLimitList::getItem(
            $request->input('age_limit_id')
        );

        /**
         * Getting status status
         */
        $statusStatus = RequestFieldStatusList::getItem(
            $request->input('status_status_id')
        );

        /**
         * Checking status existence if required
         */
        if ($vybeChangeRequest->getStatusStatus() && !$statusStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.statusStatus')
            );
        }

        /**
         * Validating appearance cases statuses
         */
        $this->vybeChangeRequestService->validateAppearanceCasesStatuses(
            $vybeChangeRequest,
            $request->input('appearance_cases')
        );

        /**
         * Getting schedules status
         */
        $schedulesStatus = RequestFieldStatusList::getItem(
            $request->input('schedules_status_id')
        );

        /**
         * Checking schedules status existence if required
         */
        if ($vybeChangeRequest->getSchedulesStatus() && !$schedulesStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/changeRequest/update.result.error.find.schedulesStatus')
            );
        }

        /**
         * Checking declined images existence
         */
        if ($request->input('declined_images_ids')) {

            try {

                /**
                 * Declining vybe images
                 */
                $this->mediaMicroservice->declineVybeImages(
                    $request->input('declined_images_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking declined videos existence
         */
        if ($request->input('declined_videos_ids')) {

            try {

                /**
                 * Declining vybe videos
                 */
                $this->mediaMicroservice->declineVybeVideos(
                    $request->input('declined_videos_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking accepted images existence
         */
        if ($request->input('accepted_images_ids')) {

            try {

                /**
                 * Accepting vybe images
                 */
                $this->mediaMicroservice->acceptVybeImages(
                    $request->input('accepted_images_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking accepted videos existence
         */
        if ($request->input('accepted_videos_ids')) {

            try {

                /**
                 * Accepting vybe videos
                 */
                $this->mediaMicroservice->acceptVybeVideos(
                    $request->input('accepted_videos_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Preparing vybe image collection response variable
         */
        $vybeImageCollectionResponse = new VybeImageCollectionResponse([], null);

        /**
         * Checking images existence
         */
        if ($vybeChangeRequest->images_ids) {

            /**
             * Getting vybe images
             */
            $vybeImageCollectionResponse = $this->mediaMicroservice->getVybeImages(
                $vybeChangeRequest->images_ids
            );
        }

        /**
         * Checking images are processed
         */
        $this->vybeChangeRequestService->checkImagesAreProcessed(
            $vybeImageCollectionResponse->images,
            $request->input('accepted_images_ids'),
            $request->input('declined_images_ids')
        );

        /**
         * Preparing vybe video collection response variable
         */
        $vybeVideoCollectionResponse = new VybeVideoCollectionResponse([], null);

        /**
         * Checking videos existence
         */
        if ($vybeChangeRequest->videos_ids) {

            /**
             * Getting vybe videos
             */
            $vybeVideoCollectionResponse = $this->mediaMicroservice->getVybeVideos(
                $vybeChangeRequest->videos_ids
            );
        }

        /**
         * Checking videos are processed
         */
        $this->vybeChangeRequestService->checkVideosAreProcessed(
            $vybeVideoCollectionResponse->videos,
            $request->input('accepted_videos_ids'),
            $request->input('declined_videos_ids')
        );

        /**
         * Updating vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->updateStatuses(
            $vybeChangeRequest,
            $titleStatus,
            $categoryStatus,
            $subcategoryStatus,
            $devicesStatus,
            $activityStatus,
            $periodStatus,
            $userCountStatus,
            $schedulesStatus,
            $orderAdvanceStatus,
            $accessStatus,
            $showcaseStatus,
            $orderAcceptStatus,
            $statusStatus
        );

        /**
         * Updating vybe change request age limit
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->updateAgeLimit(
            $vybeChangeRequest,
            $vybeAgeLimit
        );

        /**
         * Updating appearance cases statuses
         */
        $this->vybeChangeRequestService->updateAppearanceCasesStatuses(
            $vybeChangeRequest,
            $request->input('appearance_cases')
        );

        /**
         * Updating vybe change request toast message text
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->updateToastMessageText(
            $vybeChangeRequest,
            $request->input('toast_message_text')
        );

        /**
         * Getting total vybe publish request status
         */
        $requestStatus = $this->vybeChangeRequestService->getRequestStatus(
            $vybeChangeRequest,
            $request->input('declined_images_ids'),
            $request->input('declined_videos_ids')
        );

        /**
         * Checking vybe change request status
         */
        if ($requestStatus->isAccepted()) {

            /**
             * Updating vybe from vybe change request
             */
            $vybe = $this->vybeService->updateFromVybeChangeRequest(
                $this->vybeChangeRequestRepository->findFullById(
                    $vybeChangeRequest->_id
                )
            );

            /**
             * Creating vybe version
             */
            $this->vybeVersionService->create(
                $this->vybeRepository->findFullById($vybe->id)
            );

            /**
             * Updating vybe change status
             */
            $vybeChangeRequest = $this->vybeChangeRequestRepository->updateStatus(
                $vybeChangeRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeChangeAccepted(
                $vybeChangeRequest->vybe->user,
                $vybeChangeRequest->vybe
            );
        } elseif ($requestStatus->isDeclined()) {

            /**
             * Updating vybe change status
             */
            $vybeChangeRequest = $this->vybeChangeRequestRepository->updateStatus(
                $vybeChangeRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeChangeDeclined(
                $vybeChangeRequest->vybe->user,
                $vybeChangeRequest->vybe
            );
        }

        /**
         * Getting full vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->findFullById(
            $vybeChangeRequest->_id
        );

        /**
         * Updating processing admin
         */
        $this->vybeChangeRequestRepository->updateAdmin(
            $vybeChangeRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybeChangeRequest,
                new VybeChangeRequestTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybeChangeRequest])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybeChangeRequest])
                    )
                )
            ), trans('validations/api/admin/vybe/changeRequest/update.result.success')
        );
    }
}
