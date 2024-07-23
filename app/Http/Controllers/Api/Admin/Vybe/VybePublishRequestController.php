<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybePublishRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\PublishRequest\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Microservices\Media\Responses\VybeVideoCollectionResponse;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Vybe\VybePublishRequestService;
use App\Services\Vybe\VybeService;
use App\Services\Vybe\VybeVersionService;
use App\Transformers\Api\Admin\Vybe\PublishRequest\VybePublishRequestTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class VybePublishRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybePublishRequestController extends BaseController implements VybePublishRequestControllerInterface
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
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

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
     * VybePublishRequestController constructor
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

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();

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
         * Getting vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->findFullById($id);

        /**
         * Checking vybe publish request existence
         */
        if (!$vybePublishRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybePublishRequest,
                new VybePublishRequestTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybePublishRequest])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybePublishRequest])
                    )
                )
            ), trans('validations/api/admin/vybe/publishRequest/show.result.success')
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
         * Getting vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->findFullById($id);

        /**
         * Checking vybe publish request existence
         */
        if (!$vybePublishRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.vybePublishRequest')
            );
        }

        /**
         * Checking vybe publish request status
         */
        if (!$vybePublishRequest->getRequestStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.pending')
            );
        }

        /**
         * Getting a probable first CSAU suggestion
         */
        $csauSuggestion = $this->csauSuggestionRepository->findFirstForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Getting a probable first device suggestion
         */
        $deviceSuggestion = $this->deviceSuggestionRepository->findForVybePublishRequest(
            $vybePublishRequest
        );

        /**
         * Getting title status
         */
        $titleStatus = RequestFieldStatusList::getItem(
            $request->input('title_status_id')
        );

        /**
         * Checking title status existence
         */
        if (!$titleStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.titleStatus')
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
        if (!$categoryStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.categoryStatus')
            );
        } else {

            /**
             * Checking CSAU existence
             */
            if ($csauSuggestion) {

                /**
                 * Checking CSAU suggestion category status
                 */
                if ($csauSuggestion->getCategoryStatus()->isPending() &&
                    $categoryStatus->isAccepted()
                ) {
                    return $this->respondWithError(
                        trans('validations/api/admin/vybe/publishRequest/update.result.error.csau.category')
                    );
                }
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
        if ($subcategoryStatus) {

            /**
             * Checking CSAU existence
             */
            if ($csauSuggestion) {

                /**
                 * Checking CSAU suggestion subcategory status
                 */
                if ($csauSuggestion->getSubcategoryStatus() && (
                    $csauSuggestion->getSubcategoryStatus()->isPending() && $subcategoryStatus->isAccepted())
                ) {
                    return $this->respondWithError(
                        trans('validations/api/admin/vybe/publishRequest/update.result.error.csau.subcategory')
                    );
                }
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
        if (!$devicesStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.devicesStatus')
            );
        } else {

            /**
             * Checking CSAU device suggestion existence
             */
            if ($deviceSuggestion) {

                /**
                 * Checking CSAU suggestion device status
                 */
                if ($deviceSuggestion->getStatus()->isPending() &&
                    $devicesStatus->isAccepted()
                ) {
                    return $this->respondWithError(
                        trans('validations/api/admin/vybe/publishRequest/update.result.error.csau.device')
                    );
                }
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
        if (!$activityStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.activityStatus')
            );
        } else {

            /**
             * Checking CSAU existence
             */
            if ($csauSuggestion) {

                /**
                 * Checking CSAU suggestion activity status
                 */
                if ($csauSuggestion->getActivityStatus()->isPending() &&
                    $activityStatus->isAccepted()
                ) {
                    return $this->respondWithError(
                        trans('validations/api/admin/vybe/publishRequest/update.result.error.csau.activity')
                    );
                }
            }
        }

        /**
         * Getting period status
         */
        $periodStatus = RequestFieldStatusList::getItem(
            $request->input('period_status_id')
        );

        /**
         * Checking period status existence
         */
        if (!$periodStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.periodStatus')
            );
        }

        /**
         * Getting user count status
         */
        $userCountStatus = RequestFieldStatusList::getItem(
            $request->input('user_count_status_id')
        );

        /**
         * Checking user counts status existence
         */
        if (!$userCountStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.userCountStatus')
            );
        }

        /**
         * Preparing order advance status field
         */
        $orderAdvanceStatus = null;

        /**
         * Checking vybe publish request type
         */
        if (!$vybePublishRequest->getType()->isEvent()) {

            /**
             * Getting order advance status
             */
            $orderAdvanceStatus = RequestFieldStatusList::getItem(
                $request->input('order_advance_status_id')
            );

            /**
             * Checking order advance status existence
             */
            if (!$orderAdvanceStatus) {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/publishRequest/update.order_advance_status_id.required')
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
         * Checking order advance status existence
         */
        if (!$accessStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.accessStatus')
            );
        }

        /**
         * Getting showcase status
         */
        $showcaseStatus = RequestFieldStatusList::getItem(
            $request->input('showcase_status_id')
        );

        /**
         * Checking showcase status existence
         */
        if (!$showcaseStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.showcaseStatus')
            );
        }

        /**
         * Getting order accept status
         */
        $orderAcceptStatus = RequestFieldStatusList::getItem(
            $request->input('order_accept_status_id')
        );

        /**
         * Checking order accept status existence
         */
        if (!$orderAcceptStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.orderAcceptStatus')
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
         * Checking status existence
         */
        if (!$statusStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.statusStatus')
            );
        }

        /**
         * Validating appearance cases statuses
         */
        $this->vybePublishRequestService->validateAppearanceCasesStatuses(
            $vybePublishRequest,
            $request->input('appearance_cases')
        );

        /**
         * Getting schedules status
         */
        $schedulesStatus = RequestFieldStatusList::getItem(
            $request->input('schedules_status_id')
        );

        /**
         * Checking schedules status existence
         */
        if (!$schedulesStatus) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/publishRequest/update.result.error.find.schedulesStatus')
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
        if ($vybePublishRequest->images_ids) {

            /** @var VybeImageCollectionResponse $vybeImageResponseCollection */
            $vybeImageCollectionResponse = $this->mediaMicroservice->getVybeImages(
                $vybePublishRequest->images_ids
            );
        }

        /**
         * Checking images are processed
         */
        $this->vybePublishRequestService->checkImagesAreProcessed(
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
        if ($vybePublishRequest->videos_ids) {
            $vybeVideoCollectionResponse = $this->mediaMicroservice->getVybeVideos(
                $vybePublishRequest->videos_ids
            );
        }

        /**
         * Checking videos are processed
         */
        $this->vybePublishRequestService->checkVideosAreProcessed(
            $vybeVideoCollectionResponse->videos,
            $request->input('accepted_videos_ids'),
            $request->input('declined_videos_ids')
        );

        /**
         * Updating vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->updateStatuses(
            $vybePublishRequest,
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
         * Updating vybe publish request age limit
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->updateAgeLimit(
            $vybePublishRequest,
            $vybeAgeLimit
        );

        /**
         * Updating appearance cases statuses
         */
        $this->vybePublishRequestService->updateAppearanceCasesStatuses(
            $vybePublishRequest,
            $request->input('appearance_cases')
        );

        /**
         * Getting total vybe publish request status
         */
        $requestStatus = $this->vybePublishRequestService->getRequestStatus(
            $vybePublishRequest,
            $request->input('declined_images_ids'),
            $request->input('declined_videos_ids')
        );

        /**
         * Updating vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->updateRequestStatus(
            $vybePublishRequest,
            $requestStatus
        );

        /**
         * Updating vybe publish request toast message text
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->updateToastMessageText(
            $vybePublishRequest,
            $request->input('toast_message_text')
        );

        /**
         * Checking vybe publish request status
         */
        if ($requestStatus->isAccepted()) {

            /**
             * Updating vybe status
             */
            $vybe = $this->vybeRepository->updateStatus(
                $vybePublishRequest->vybe,
                VybeStatusList::getPublishedItem()
            );

            /**
             * Updating vybe version
             */
            $vybe = $this->vybeRepository->increaseVersion(
                $vybe
            );

            /**
             * Deleting all vybe support
             */
            $this->vybeService->deleteAllVybeSupport(
                $vybe
            );

            /**
             * Update vybe
             */
            $this->vybeRepository->updateAgeLimit(
                $vybe,
                $vybePublishRequest->getAgeLimit()
            );

            /**
             * Creating vybe version
             */
            $this->vybeVersionService->create(
                $this->vybeRepository->findFullById($vybe->id)
            );

            /**
             * Updating user seller status
             */
            $this->userBalanceRepository->updateStatus(
                $vybePublishRequest->vybe
                    ->user
                    ->getSellerBalance(),
                UserBalanceStatusList::getActive()
            );

            /**
             * Sending email notification to all followers
             */
            $this->vybeService->sendToAllFollowers(
                $vybePublishRequest->vybe->user,
                $vybePublishRequest->vybe->getType()
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybePublishAccepted(
                $vybePublishRequest->vybe->user,
                $vybePublishRequest->vybe
            );
        } elseif ($requestStatus->isDeclined()) {

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybePublishDeclined(
                $vybePublishRequest->vybe->user,
                $vybePublishRequest->vybe
            );
        }

        /**
         * Updating processing admin
         */
        $this->vybePublishRequestRepository->updateAdmin(
            $vybePublishRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybePublishRequestRepository->findFullById(
                    $vybePublishRequest->_id
                ),
                new VybePublishRequestTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybePublishRequest])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybePublishRequest])
                    )
                )
            ), trans('validations/api/admin/vybe/publishRequest/update.result.success')
        );
    }
}
