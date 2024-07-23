<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybePublishRequestControllerInterface;
use App\Http\Requests\Api\General\Vybe\PublishRequest\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Suggestion\CsauSuggestionRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Repositories\Vybe\VybePublishRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybePublishRequestService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class VybePublishRequestController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybePublishRequestController extends BaseController implements VybePublishRequestControllerInterface
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
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CsauSuggestionRepository
     */
    protected CsauSuggestionRepository $csauSuggestionRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

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
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CsauSuggestionRepository csauSuggestionRepository */
        $this->csauSuggestionRepository = new CsauSuggestionRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();

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
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.find')
            );
        }

        /**
         * Checkin is a vybe owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.completed')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isDraft()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.status')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.request')
            );
        }

        /**
         * Validating 1-st step
         */

        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Getting subcategory
         */
        $subcategory = $this->categoryRepository->findById(
            $request->input('subcategory_id')
        );

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
                    'activity_id' => trans('validations/api/general/vybe/publishRequest/update.result.error.activity.absence')
                ]);
            }
        } else {

            /**
             * Checking activity suggestion existence
             */
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => trans('validations/api/general/vybe/publishRequest/update.result.error.activity.doubling')
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
         * Preparing devices variable
         */
        $devices = null;

        /**
         * Checking device ids existence
         */
        if ($request->input('devices_ids')) {

            /**
             * Getting devices
             */
            $devices = $this->deviceRepository->getByIds(
                $request->input('devices_ids')
            );
        }

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
         * Checking vybe type existence
         */
        if (!$vybe->getType()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/update.result.error.vybeType')
            );
        }

        /**
         * Checking vybe type
         */
        if (!$vybe->getType()->isEvent()) {
            if (!$request->input('order_advance')) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/publishRequest/update.order_advance.required')
                );
            }
        }

        /**
         * Getting order advance
         */
        $orderAdvance = !$vybe->getType()->isEvent() ?
            $request->input('order_advance') :
            null;

        /**
         * Validating schedules
         */
        $this->vybeService->validateSchedules(
            $vybe->getType(),
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
         * Validating 5-th step
         */

        /**
         * Getting vybe access
         */
        $vybeAccessListItem = VybeAccessList::getItem(
            $request->input('access_id')
        );

        /**
         * Checking is vybe private
         */
        if ($vybeAccessListItem->isPrivate()) {

            /**
             * Checking access password existence
             */
            if (!$request->input('access_password')) {
                return $this->respondWithErrors([
                    'access_password' => trans('validations/api/general/vybe/publishRequest/update.result.error.accessPassword.required')
                ]);
            }
        } else {

            /**
             * Checking access password existence
             */
            if ($request->input('access_password')) {
                return $this->respondWithErrors([
                    'access_password' => trans('validations/api/general/vybe/publishRequest/update.result.error.accessPassword.excess')
                ]);
            }
        }

        /**
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem(
            $request->input('showcase_id')
        );

        /**
         * Getting vybe showcase
         */
        $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem(
            $request->input('order_accept_id')
        );

        /**
         * Updating 1-st step
         */

        /**
         * Updating vybe
         */
        $vybe = $this->vybeService->updateFirstStep(
            $vybe,
            $request->input('title'),
            $category,
            $request->input('category_suggestion'),
            $subcategory,
            $request->input('subcategory_suggestion'),
            $devices,
            $request->input('device_suggestion'),
            $activity,
            $request->input('activity_suggestion'),
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Updating vybe type
         */
        $vybe = $this->vybeService->updateVybeType(
            $vybe
        );

        /**
         * Updating 2-nd step
         */

        /**
         * Deleting existing vybe appearance cases
         */
        $this->vybeService->deleteAppearanceCasesForVybe(
            $vybe
        );

        /**
         * Creating vybe appearance cases
         */
        $this->vybeService->createAppearanceCases(
            $vybe,
            $request->input('appearance_cases')
        );

        /**
         * Updating 3-rd step
         */

        /**
         * Deleting existing vybe schedules
         */
        $this->scheduleRepository->deleteForceForVybe(
            $vybe
        );

        /**
         * Updating vybe schedules
         */
        $this->vybeService->createSchedules(
            $vybe,
            $request->input('schedules')
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateOrderAdvance(
            $vybe,
            $orderAdvance
        );

        /**
         * Updating 4-th step
         */

        /**
         * Checking deleted images existence
         */
        if ($request->input('deleted_images_ids')) {

            try {

                /**
                 * Updating vybe
                 */
                $vybe = $this->vybeRepository->updateMediaIds(
                    $vybe,
                    array_diff(
                        $vybe->images_ids,
                        $request->input('deleted_images_ids')
                    ),
                    null
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
         * Checking deleted videos existence
         */
        if ($request->input('deleted_videos_ids')) {

            try {

                /**
                 * Updating vybe
                 */
                $vybe = $this->vybeRepository->updateMediaIds(
                    $vybe,
                    null,
                    array_diff(
                        $vybe->videos_ids,
                        $request->input('deleted_videos_ids')
                    )
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
                        $this->vybeService->getImagesFromFiles(
                            $request->input('files')
                        )
                    );

                    /**
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        array_merge(
                            $vybe->images_ids ?
                                $vybe->images_ids :
                                [],
                            $vybeImageCollection->images
                                ->pluck('id')
                                ->toArray()
                        ),
                        null
                    );
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
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        null,
                        array_merge(
                            $vybe->videos_ids ?
                                $vybe->videos_ids :
                                [],
                            $vybeVideoCollection->videos
                                ->pluck('id')
                                ->toArray()
                        )
                    );
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
         * Checking files existence
         */
        if (!$this->vybeService->checkFilesExistence(
            $vybe,
            $request->input('files')
        )) {
            return $this->respondWithErrors([
                'files' => trans('validations/api/general/vybe/publishRequest/update.result.error.files.absence')
            ]);
        }

        /**
         * Updating 5-th step
         */

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateFifthStep(
            $vybe,
            $vybeAccessListItem,
            $vybeShowcaseListItem,
            VybeStatusList::getDraftItem(),
            $vybeOrderAcceptListItem,
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateAccessPassword(
            $vybe,
            $request->input('access_password')
        );

        /**
         * Creating vybe publish request
         */
        $this->vybePublishRequestService->executePublishRequestForVybe(
            $this->vybeRepository->findFullById(
                $vybe->id
            )
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
            trans('validations/api/general/vybe/publishRequest/update.result.success')
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
                trans('validations/api/general/vybe/publishRequest/close.result.error.find.vybe')
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
                trans('validations/api/general/vybe/publishRequest/close.result.error.owner')
            );
        }

        /**
         * Getting vybe publish request
         */
        $vybePublishRequest = $this->vybePublishRequestRepository->findLastForVybe(
            $vybe
        );

        /**
         * Checking vybe publish request existence
         */
        if (!$vybePublishRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/close.result.error.find.vybePublishRequest')
            );
        }

        /**
         * Checking vybe publish request status
         */
        if (!$vybePublishRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/close.result.error.status')
            );
        }

        /**
         * Updating vybe publish request
         */
        $this->vybePublishRequestRepository->updateShown(
            $vybePublishRequest,
            true
        );

        /**
         * Releasing vybe publish request counter-caches
         */
        $this->adminNavbarService->releaseVybePublishRequestCache();

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
            trans('validations/api/general/vybe/publishRequest/close.result.success')
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
                trans('validations/api/general/vybe/publishRequest/cancel.result.error.find.vybe')
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
                trans('validations/api/general/vybe/publishRequest/cancel.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/cancel.result.error.completed')
            );
        }

        /**
         * Checking vybe pending publish request existence
         */
        if (!$vybe->publishRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/publishRequest/cancel.result.error.find.publishRequest')
            );
        }

        /**
         * Updating vybe publish request
         */
        $this->vybePublishRequestRepository->updateRequestStatus(
            $vybe->publishRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Deleting all pending csau suggestions
         */
        $this->csauSuggestionRepository->deletePendingForVybePublishRequest(
            $vybe->publishRequest
        );

        /**
         * Deleting all pending device suggestions
         */
        $this->deviceSuggestionRepository->deletePendingForVybePublishRequest(
            $vybe->publishRequest
        );

        /**
         * Releasing vybe publish request counter-caches
         */
        $this->adminNavbarService->releaseVybePublishRequestCache();

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
            trans('validations/api/general/vybe/publishRequest/cancel.result.success')
        );
    }
}
