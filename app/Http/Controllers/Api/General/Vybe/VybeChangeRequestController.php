<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeChangeRequestControllerInterface;
use App\Http\Requests\Api\General\Vybe\ChangeRequest\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Suggestion\CsauSuggestionService;
use App\Services\Vybe\VybeChangeRequestService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class VybeChangeRequestController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeChangeRequestController extends BaseController implements VybeChangeRequestControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var CsauSuggestionService
     */
    protected CsauSuggestionService $csauSuggestionService;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

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
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var CsauSuggestionService csauSuggestionService */
        $this->csauSuggestionService = new CsauSuggestionService();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

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

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws TranslateException
     * @throws ValidationException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Validating 1-st step
         */

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.find')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.completed')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isPublished() &&
            !$vybe->getStatus()->isPaused()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.status')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.request')
            );
        }

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $request->input('activity_id')
        );

        /**
         * Checking possible doubling with an activity suggestion
         */
        if (!$activity) {

            /**
             * Checking activity suggestion existence
             */
            if (!$request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => trans('validations/api/general/vybe/changeRequest/update.result.error.activity.absence')
                ]);
            }
        } else {

            /**
             * Checking activity suggestion existence
             */
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => trans('validations/api/general/vybe/changeRequest/update.result.error.activity.doubling')
                ]);
            }
        }

        /**
         * Getting vybe period
         */
        $vybePeriodListItem = VybePeriodList::getItem(
            $request->input('period_id')
        );

        /**
         * Validating 2-nd step
         */

        /**
         * Validating appearance cases
         */
        $this->vybeService->validateAppearanceCases(
            $request->input('appearance_cases')
        );

        /**
         * Validating 3-rd step
         */

        /**
         * Getting vybe type
         */
        $vybeTypeListItem = $this->vybeService->getVybeTypeByParameters(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type
         */
        if (!$vybeTypeListItem->isEvent()) {
            if (!$request->input('order_advance')) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/changeRequest/update.order_advance.required')
                );
            }
        }

        /**
         * Validating schedules
         */
        $this->vybeService->validateSchedules(
            $vybeTypeListItem,
            $request->input('schedules')
        );

        /**
         * Validating 4-th step
         */

        /**
         * Checking files existence
         */
        if ($request->input('files')) {

            /**
             * Checking files upload availability
             */
            if (count($request->input('files')) > 5) {
                return $this->respondWithErrors([
                    'files' => trans('validations/api/general/vybe/changeRequest/update.result.error.files.many')
                ]);
            }

            /**
             * Validating files
             */
            $this->vybeService->validateFiles(
                $request->input('files')
            );
        }

        /**
         * Checking files upload availability
         */
        $this->vybeService->checkFilesUploadAvailability(
            $request->input('files'),
            $vybe->images_ids,
            $vybe->videos_ids,
            $request->input('deleted_images_ids'),
            $request->input('deleted_videos_ids')
        );

        /**
         * Getting vybe status
         */
        $vybeStatus = VybeStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Validation completed. Updating
         */

        /**
         * Preparing changed images and videos variables
         */
        $uploadedImagesIds = null;
        $uploadedVideosIds = null;

        /**
         * Checking files existence
         */
        if ($request->input('files')) {

            try {

                /**
                 * Getting file images
                 */
                $fileImages = $this->vybeService->getImagesFromFiles(
                    $request->input('files')
                );

                /**
                 * Checking file images existence
                 */
                if (!empty($fileImages)) {

                    /** @var VybeImageCollectionResponse $vybeImages */
                    $vybeImageCollection = $this->mediaMicroservice->storeVybeImages(
                        $fileImages
                    );

                    /**
                     * Checking uploaded vybe images
                     */
                    if ($vybeImageCollection->images->count()) {

                        /**
                         * Getting uploaded images ids
                         */
                        $uploadedImagesIds = $vybeImageCollection->images
                            ->pluck('id')
                            ->toArray();
                    }
                }

            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }

            try {

                /**
                 * Getting file videos
                 */
                $fileVideos = $this->vybeService->getVideosFromFiles(
                    $request->input('files')
                );

                /**
                 * Checking file videos existence
                 */
                if (!empty($fileVideos)) {

                    /**
                     * Getting vybe video response collection
                     */
                    $vybeVideoCollection = $this->mediaMicroservice->storeVybeVideos(
                        $fileVideos
                    );

                    /**
                     * Checking uploaded vybe images
                     */
                    if ($vybeVideoCollection->videos->count()) {

                        /**
                         * Getting uploaded videos ids
                         */
                        $uploadedVideosIds = $vybeVideoCollection->videos
                            ->pluck('id')
                            ->toArray();
                    }
                }

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
         * Getting changes images ids
         */
        $changedImagesIds = $this->vybeService->getChangedMediaIds(
            $vybe->images_ids,
            $request->input('deleted_images_ids'),
            $uploadedImagesIds
        );

        /**
         * Getting changes videos ids
         */
        $changedVideosIds = $this->vybeService->getChangedMediaIds(
            $vybe->videos_ids,
            $request->input('deleted_videos_ids'),
            $uploadedVideosIds
        );

        /**
         * Checking files existence
         */
        if (!$this->vybeService->checkFilesExistence(
            $vybe,
            $request->input('files')
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/update.result.error.files.absence')
            );
        }

        /**
         * Checking if full vybe change request has changes
         */
        if (!$this->vybeChangeRequestService->hasVybeChangeRequestChanges(
            $vybe,
            $request->all(),
            $changedImagesIds,
            $changedVideosIds
        ) && !$this->vybeChangeRequestService->haveAppearanceCasesChanges(
            $vybe,
            $request->input('appearance_cases')
        ) && !$this->vybeChangeRequestService->haveSchedulesChanges(
            $vybe,
            $request->input('schedules')
        )) {

            /**
             * Checking status existence
             */
            if ($vybe->getStatus()->code == $vybeStatus->code) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/changeRequest/update.result.error.changes')
                );
            }
        } else {

            /**
             * Creating vybe change request
             */
            $vybeChangeRequest = $this->vybeChangeRequestService->createVybeChangeRequest(
                $vybe,
                $request->all(),
                $changedImagesIds,
                $changedVideosIds
            );

            /**
             * Checking vybe change request existence
             */
            if (!$vybeChangeRequest) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/changeRequest/update.result.error.create')
                );
            }

            /**
             * Checking appearance cases existence
             */
            if ($request->input('appearance_cases')) {

                /**
                 * Creating vybe change request appearance cases
                 */
                $this->vybeChangeRequestService->createAppearanceCases(
                    $vybeChangeRequest,
                    $request->input('appearance_cases')
                );
            }

            /**
             * Checking appearance cases existence
             */
            if ($request->input('schedules')) {

                /**
                 * Creating vybe change request schedules
                 */
                $this->vybeChangeRequestService->createSchedules(
                    $vybeChangeRequest,
                    $request->input('schedules')
                );
            }

            /**
             * Executing CSAU suggestions to vybe change request
             */
            $this->csauSuggestionService->executeForVybeChangeRequest(
                $vybeChangeRequest
            );

            /**
             * Checking vybe public request device suggestion existence
             */
            if (!is_null($vybeChangeRequest->device_suggestion)) {

                /**
                 * Creating a device suggestion
                 */
                $deviceSuggestion = $this->deviceSuggestionRepository->store(
                    null,
                    $vybeChangeRequest,
                    null,
                    $vybeChangeRequest->device_suggestion
                );

                /**
                 * Checking a device suggestion created
                 */
                if ($deviceSuggestion) {

                    /**
                     * Update vybe change request device suggestion
                     */
                    $this->vybeChangeRequestRepository->updateDeviceSuggestion(
                        $vybeChangeRequest,
                        $deviceSuggestion
                    );
                }
            }

            /**
             * Checking access password existence
             */
            if ($request->input('access_password')) {

                /**
                 * Updating vybe access password
                 */
                $this->vybeRepository->updateAccessPassword(
                    $vybe,
                    $request->input('access_password')
                );
            }

            /**
             * Releasing vybe change request counter-caches
             */
            $this->adminNavbarService->releaseVybeChangeRequestCache();
        }

        /**
         * Updating vybe
         */
        $this->vybeRepository->updateStatus(
            $vybe,
            $vybeStatus
        );

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/changeRequest/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function close(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/close.result.error.find.vybe')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/close.result.error.owner')
            );
        }

        /**
         * Getting vybe change request
         */
        $vybeChangeRequest = $this->vybeChangeRequestRepository->findLastForVybe(
            $vybe
        );

        /**
         * Checking vybe change request existence
         */
        if (!$vybeChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/close.result.error.find.vybeChangeRequest')
            );
        }

        /**
         * Checking is vybe change request status
         */
        if (!$vybeChangeRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/close.result.error.status')
            );
        }

        /**
         * Updating vybe change request
         */
        $this->vybeChangeRequestRepository->updateShown(
            $vybeChangeRequest,
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/changeRequest/close.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancel(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/cancel.result.error.find.vybe')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/cancel.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/cancel.result.error.completed')
            );
        }

        /**
         * Checking vybe pending change request existence
         */
        if (!$vybe->changeRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/changeRequest/cancel.result.error.find.changeRequest')
            );
        }

        /**
         * Updating vybe change request
         */
        $this->vybeChangeRequestRepository->updateStatus(
            $vybe->changeRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Deleting all pending csau suggestions
         */
        $this->csauSuggestionRepository->deletePendingForVybeChangeRequest(
            $vybe->changeRequest
        );

        /**
         * Deleting all pending device suggestions
         */
        $this->deviceSuggestionRepository->deletePendingForVybeChangeRequest(
            $vybe->changeRequest
        );

        /**
         * Releasing vybe change request counter-caches
         */
        $this->adminNavbarService->releaseVybeChangeRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/changeRequest/cancel.result.success')
        );
    }
}
