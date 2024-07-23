<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeStepControllerInterface;
use App\Http\Requests\Api\General\Vybe\Step\StoreChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\StoreNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFifthStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFifthStepNext;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFirstStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFirstStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFourthStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateFourthStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateSecondStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateSecondStepNextRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateThirdStepChangesRequest;
use App\Http\Requests\Api\General\Vybe\Step\UpdateThirdStepNextRequest;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Step\VybeStepList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\AppearanceCase\AppearanceCaseRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Platform\PlatformService;
use App\Services\Vybe\VybePublishRequestService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\Vybe\VybeTransformer;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class VybeStepController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeStepController extends BaseController implements VybeStepControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var AppearanceCaseRepository
     */
    protected AppearanceCaseRepository $appearanceCaseRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var PlatformService
     */
    protected PlatformService $platformService;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

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
     * VybeStepController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AppearanceCaseRepository appearanceCaseRepository */
        $this->appearanceCaseRepository = new AppearanceCaseRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var PlatformService platformService */
        $this->platformService = new PlatformService();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

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
     * @param StoreChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function storeChanges(
        StoreChangesRequest $request
    ) : JsonResponse
    {
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
         * Getting vybe
         */
        $vybeType = $this->vybeService->getVybeType(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type user count
         */
        if ($vybeType &&
            $request->input('user_count')
        ) {
            if ($this->vybeService->isUserCountNotAllowed(
                $vybeType,
                $request->input('user_count')
            )) {
                return $this->respondWithErrors([
                    'user_count' => [
                        trans('validations/api/general/vybe/step/storeChanges.result.error.userCount.max')
                    ]
                ]);
            }
        }

        /**
         * Creating vybe
         */
        $vybe = $this->vybeService->storeFirstStep(
            AuthService::user(),
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
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/storeChanges.result.error')
            );
        }

        /**
         * Updating vybe type
         */
        $vybe = $this->vybeService->updateVybeType(
            $vybe
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybeTransformer
            ), trans('validations/api/general/vybe/step/storeChanges.result.success')
        );
    }

    /**
     * @param StoreNextRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function storeNext(
        StoreNextRequest $request
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Checking possible doubling with a category suggestion
         */
        if (!$category) {

            /**
             * Checking category suggestion existence
             */
            if (!$request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/general/vybe/step/storeNext.result.error.category.absence')
                    ]
                ]);
            }
        } else {

            /**
             * Checking category suggestion existence
             */
            if ($request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/general/vybe/step/storeNext.result.error.category.doubling')
                    ]
                ]);
            }
        }

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
                    'activity_id' => trans('validations/api/general/vybe/step/storeNext.result.error.activity.absence')
                ]);
            }
        } else {

            /**
             * Checking activity suggestion existence
             */
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => [
                        trans('validations/api/general/vybe/step/storeNext.result.error.activity.doubling')
                    ]
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
         * Getting vybe
         */
        $vybeType = $this->vybeService->getVybeType(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type user count
         */
        if ($vybeType &&
            $request->input('user_count')
        ) {
            if ($this->vybeService->isUserCountNotAllowed(
                $vybeType,
                $request->input('user_count')
            )) {
                return $this->respondWithErrors([
                    'user_count' => [
                        trans('validations/api/general/vybe/step/storeNext.result.error.userCount.max')
                    ]
                ]);
            }
        }

        /**
         * Creating vybe
         */
        $vybe = $this->vybeService->storeFirstStep(
            AuthService::user(),
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
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/storeNext.result.error')
            );
        }

        /**
         * Updating vybe type
         */
        $vybe = $this->vybeService->updateVybeType(
            $vybe
        );

        /**
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getSecond()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybeTransformer
            ), trans('validations/api/general/vybe/step/storeNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFirstStepChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateFirstStepChanges(
        int $id,
        UpdateFirstStepChangesRequest $request
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
                trans('validations/api/general/vybe/step/updateFirstStepChanges.result.error.find')
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
                trans('validations/api/general/vybe/step/updateFirstStepChanges.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFirstStepChanges.result.error.completed')
            );
        }

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
         * Getting vybe
         */
        $vybeType = $this->vybeService->getVybeType(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type user count
         */
        if ($vybeType &&
            $request->input('user_count')
        ) {
            if ($this->vybeService->isUserCountNotAllowed(
                $vybeType,
                $request->input('user_count')
            )) {
                return $this->respondWithErrors([
                    'user_count' => [
                        trans('validations/api/general/vybe/step/updateFirstStepChanges.result.error.userCount.max')
                    ]
                ]);
            }
        }

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
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getFirst()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateFirstStepChanges.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFirstStepNextRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateFirstStepNext(
        int $id,
        UpdateFirstStepNextRequest $request
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
                trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.find')
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
                trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.completed')
            );
        }

        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Checking possible doubling with a category suggestion
         */
        if (!$category) {

            /**
             * Checking category suggestion existence
             */
            if (!$request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.category.absence')
                    ]
                ]);
            }
        } else {

            /**
             * Checking category suggestion existence
             */
            if ($request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.category.doubling')
                    ]
                ]);
            }
        }

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
                    'activity_id' => [
                        trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.activity.absence')
                    ]
                ]);
            }
        } else {

            /**
             * Checking activity suggestion existence
             */
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => [
                        trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.activity.doubling')
                    ]
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
         * Getting vybe
         */
        $vybeType = $this->vybeService->getVybeType(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type user count
         */
        if ($vybeType &&
            $request->input('user_count')
        ) {
            if ($this->vybeService->isUserCountNotAllowed(
                $vybeType,
                $request->input('user_count')
            )) {
                return $this->respondWithErrors([
                    'user_count' => [
                        trans('validations/api/general/vybe/step/updateFirstStepNext.result.error.userCount.max')
                    ]
                ]);
            }
        }

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
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getSecond()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateFirstStepNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateSecondStepChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     */
    public function updateSecondStepChanges(
        int $id,
        UpdateSecondStepChangesRequest $request
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
                trans('validations/api/general/vybe/step/updateSecondStepChanges.result.error.find')
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
                trans('validations/api/general/vybe/step/updateSecondStepChanges.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateSecondStepChanges.result.error.completed')
            );
        }

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
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getSecond()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateSecondStepChanges.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateSecondStepNextRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws TranslateException
     * @throws ValidationException
     */
    public function updateSecondStepNext(
        int $id,
        UpdateSecondStepNextRequest $request
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
                trans('validations/api/general/vybe/step/updateSecondStepNext.result.error.find')
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
                trans('validations/api/general/vybe/step/updateSecondStepNext.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateSecondStepNext.result.error.completed')
            );
        }

        /**
         * Checking step forward availability
         */
        if (!$this->vybeService->checkStepForward(
            $vybe,
            VybeStepList::getThird()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateSecondStepNext.result.error.stepForward')
            );
        }

        /**
         * Validating appearance cases
         */
        $this->vybeService->validateAppearanceCases(
            $request->input('appearance_cases')
        );

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
         * Updating vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getThird()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateSecondStepNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateThirdStepChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateThirdStepChanges(
        int $id,
        UpdateThirdStepChangesRequest $request
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
                trans('validations/api/general/vybe/step/updateThirdStepChanges.result.error.find')
            );
        }

        /**
         * Checking is a vybe owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateThirdStepChanges.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateThirdStepChanges.result.error.completed')
            );
        }

        /**
         * Deleting existing vybe schedules
         */
        $this->scheduleRepository->deleteForceForVybe(
            $vybe
        );

        /**
         * Updating draft vybe schedules
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
            $request->input('order_advance')
        );

        /**
         * Updating vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getThird()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateThirdStepChanges.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateThirdStepNextRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws ValidationException
     */
    public function updateThirdStepNext(
        int $id,
        UpdateThirdStepNextRequest $request
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
                trans('validations/api/general/vybe/step/updateThirdStepNext.result.error.find')
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
                trans('validations/api/general/vybe/step/updateThirdStepNext.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateThirdStepNext.result.error.completed')
            );
        }

        /**
         * Checking step forward availability
         */
        if (!$this->vybeService->checkStepForward(
            $vybe,
            VybeStepList::getFourth()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateThirdStepNext.result.error.stepForward')
            );
        }

        /**
         * Checking vybe type existence
         */
        if (!$vybe->getType()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateThirdStepNext.result.error.vybeType')
            );
        }

        /**
         * Checking vybe type
         */
        if (!$vybe->getType()->isEvent()) {
            if (!$request->input('order_advance')) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/step/updateThirdStepNext.order_advance.required')
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
         * Updating vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getFourth()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateThirdStepNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFourthStepChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function updateFourthStepChanges(
        int $id,
        UpdateFourthStepChangesRequest $request
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
                trans('validations/api/general/vybe/step/updateFourthStepChanges.result.error.find')
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
                trans('validations/api/general/vybe/step/updateFourthStepChanges.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFourthStepChanges.result.error.completed')
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
         * Checking deleted images existence
         */
        if ($request->input('deleted_images_ids')) {

            try {

                /**
                 * Deleting images
                 */
                $this->mediaMicroservice->deleteVybeImages(
                    $request->input('deleted_images_ids')
                );

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
                 * Deleting videos
                 */
                $this->mediaMicroservice->deleteVybeVideos(
                    $request->input('deleted_videos_ids')
                );

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

            /**
             * Validating files
             */
            $this->vybeService->validateFiles(
                $request->input('files')
            );

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
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getFourth()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateThirdStepNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFourthStepNextRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function updateFourthStepNext(
        int $id,
        UpdateFourthStepNextRequest $request
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
                trans('validations/api/general/vybe/step/updateFourthStepNext.result.error.find')
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
                trans('validations/api/general/vybe/step/updateFourthStepNext.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFourthStepNext.result.error.completed')
            );
        }

        /**
         * Checking step forward availability
         */
        if (!$this->vybeService->checkStepForward(
            $vybe,
            VybeStepList::getFifth()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFourthStepNext.result.error.stepForward')
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
         * Checking deleted images existence
         */
        if ($request->input('deleted_images_ids')) {

            try {

                /**
                 * Deleting images
                 */
                $this->mediaMicroservice->deleteVybeImages(
                    $request->input('deleted_images_ids')
                );

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
                 * Deleting videos
                 */
                $this->mediaMicroservice->deleteVybeVideos(
                    $request->input('deleted_videos_ids')
                );

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

            /**
             * Validating files
             */
            $this->vybeService->validateFiles(
                $request->input('files')
            );

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
                'files' => [
                    trans('validations/api/general/vybe/step/updateFourthStepNext.result.error.files.absence')
                ]
            ]);
        }

        /**
         * Updating vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getFifth()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateFourthStepNext.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFifthStepChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateFifthStepChanges(
        int $id,
        UpdateFifthStepChangesRequest $request
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
                trans('validations/api/general/vybe/step/updateFifthStepChanges.result.error.find')
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
                trans('validations/api/general/vybe/step/updateFifthStepChanges.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFifthStepChanges.result.error.completed')
            );
        }

        /**
         * Getting vybe access
         */
        $vybeAccessListItem = VybeAccessList::getItem(
            $request->input('access_id')
        );

        /**
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem(
            $request->input('showcase_id')
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateFifthStep(
            $vybe,
            $vybeAccessListItem,
            $vybeShowcaseListItem,
            VybeStatusList::getDraftItem()
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateAccessPassword(
            $vybe,
            $request->input('access_password')
        );

        /**
         * Updating vybe current step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getFifth()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateFifthStepChanges.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateFifthStepNext $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function updateFifthStepNext(
        int $id,
        UpdateFifthStepNext $request
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
                trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.find')
            );
        }

        /**
         * Checking is a vybe owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.completed')
            );
        }

        /**
         * Checking step forward availability
         */
        if (!$this->vybeService->checkStepForward(
            $vybe,
            VybeStepList::getCompleted()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.stepForward')
            );
        }

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
            if (!$request->input('access_password')) {
                return $this->respondWithErrors([
                    'access_password' => [
                        trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.accessPassword.required')
                    ]
                ]);
            }
        } else {
            if ($request->input('access_password')) {
                return $this->respondWithErrors([
                    'access_password' => [
                        trans('validations/api/general/vybe/step/updateFifthStepNext.result.error.accessPassword.excess')
                    ]
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
         * Getting vybe status
         */
        $vybeStatusListItem = VybeStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Checking vybe status
         */
        if ($vybeStatusListItem->isDeleted()) {

            /**
             * Checking vybe images existence
             */
            if ($vybe->images_ids) {

                try {

                    /**
                     * Deleting vybe images
                     */
                    $this->mediaMicroservice->deleteVybeImages(
                        $vybe->images_ids
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
             * Checking vybe images existence
             */
            if ($vybe->videos_ids) {

                try {

                    /**
                     * Deleting vybe videos
                     */
                    $this->mediaMicroservice->deleteVybeVideos(
                        $vybe->videos_ids
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
             * Deleting vybe support
             */
            $this->vybeService->deleteAllVybeSupport(
                $vybe
            );

            /**
             * Deleting vybe
             */
            $this->vybeRepository->forceDelete(
                $vybe
            );

            /**
             * Returning without media
             */
            return $this->respondWithSuccess([
                'vybe' => null
            ], trans('validations/api/general/vybe/step/updateFifthStepNext.result.success.deleted'));
        }

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateFifthStep(
            $vybe,
            $vybeAccessListItem,
            $vybeShowcaseListItem,
            VybeStatusList::getDraftItem(),
            VybeOrderAcceptList::getManual()
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateAccessPassword(
            $vybe,
            $request->input('access_password')
        );

        /**
         * Update vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getCompleted()
        );

        /**
         * Checking vybe is able to execute publish request
         */
        if ($vybe->getStep()->isCompleted() &&
            $vybeStatusListItem->isPublished()
        ) {

            /**
             * Creating vybe publish request
             */
            $this->vybePublishRequestService->executePublishRequestForVybe(
                $vybe
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById($vybe->id),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/step/updateFifthStepNext.result.success.completed')
        );
    }
}
