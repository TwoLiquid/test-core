<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Vybe\DestroyRequest;
use App\Http\Requests\Api\Admin\Vybe\IndexRequest;
use App\Http\Requests\Api\Admin\Vybe\ShowRequest;
use App\Http\Requests\Api\Admin\Vybe\StoreRequest;
use App\Http\Requests\Api\Admin\Vybe\UpdateRequest;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Step\VybeStepList;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Platform\PlatformService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeChangeRequestService;
use App\Services\Vybe\VybeService;
use App\Services\Vybe\VybeVersionService;
use App\Transformers\Api\Admin\Vybe\Form\VybeFormTransformer;
use App\Transformers\Api\Admin\Vybe\Vybe\VybePageTransformer;
use App\Transformers\Api\Admin\Vybe\VybeList\VybeListPageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var PlatformService
     */
    protected PlatformService $platformService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeVersionService
     */
    protected VybeVersionService $vybeVersionService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var PlatformService platformService */
        $this->platformService = new PlatformService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeVersionService vybeVersionService */
        $this->vybeVersionService = new VybeVersionService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybes with pagination
         */
        $vybes = $this->vybeRepository->getAllFiltered(
            $request->input('vybe_id'),
            $request->input('categories_ids'),
            $request->input('subcategories_ids'),
            $request->input('activities_ids'),
            $request->input('types_ids'),
            $request->input('users_ids'),
            $request->input('vybe_title'),
            $request->input('price'),
            $request->input('units_ids'),
            $request->input('statuses_ids'),
            $request->input('sort_by'),
            $request->input('sort_order')
        );

        /**
         * Getting vybes for labels
         */
        $vybesForLabels = $this->vybeRepository->getAllFilteredForAdminLabels(
            $request->input('vybe_id'),
            $request->input('categories_ids'),
            $request->input('subcategories_ids'),
            $request->input('activities_ids'),
            $request->input('types_ids'),
            $request->input('users_ids'),
            $request->input('vybe_title'),
            $request->input('price'),
            $request->input('units_ids')
        );

        /**
         * Getting vybe statuses with counts
         */
        $vybeStatuses = $this->vybeService->getForAdminStatusesByIds(
            $vybesForLabels
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Paginating vybes
             */
            $paginatedVybes = paginateCollection(
                $vybes,
                $request->input('per_page') ?
                    $request->input('per_page') :
                    $this->vybeRepository->getPerPage()
            );

            return $this->setPagination($paginatedVybes)->respondWithSuccess(
                $this->transformItem([],
                    new VybeListPageTransformer(
                        new Collection($paginatedVybes->items()),
                        $vybeStatuses,
                    )
                )['vybe_list'],
                trans('validations/api/admin/vybe/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeListPageTransformer(
                    $vybes,
                    $vybeStatuses
                )
            )['vybe_list'],
            trans('validations/api/admin/vybe/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id,
        ShowRequest $request
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
            return $this->respondNotFound(
                trans('validations/api/admin/vybe/show.result.error.find')
            );
        }

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
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    )
                )
            )['vybe_page'],
            trans('validations/api/admin/vybe/show.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getFormData(
        int $id
    ) : JsonResponse
    {
        $user = $this->userRepository->findByIdForAdmin($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/getFormData.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([], new VybeFormTransformer(
                null,
                $user,
                $this->platformIconRepository->getByPlatforms(
                    $this->platformRepository->getAll()
                )
            )), trans('validations/api/admin/vybe/getFormData.result.success')
        );
    }

    /**
     * @param StoreRequest $request
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
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Validating 1-st step
         */

        /**
         * Getting user
         */
        $user = $this->userRepository->findById(
            $request->input('user_id')
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/store.result.error.find.user')
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
            if (!$request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/admin/vybe/store.result.error.category.absence')
                    ]
                ]);
            }
        } else {
            if ($request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/admin/vybe/store.result.error.category.doubling')
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
         * Checking possible doubling with a subcategory suggestion
         */
        if ($subcategory) {
            if ($request->input('subcategory_suggestion')) {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/store.result.error.subcategory.doubling')
                );
            }
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
            if (!$request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => [
                        trans('validations/api/admin/vybe/store.result.error.activity.absence')
                    ]
                ]);
            }
        } else {
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => [
                        trans('validations/api/admin/vybe/store.result.error.activity.doubling')
                    ]
                ]);
            }
        }

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
                    trans('validations/api/admin/vybe/store.order_advance.required')
                );
            }
        }

        /**
         * Getting order advance
         */
        $orderAdvance = !$vybeTypeListItem->isEvent() ?
            $request->input('order_advance') :
            null;

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
         * Checking files upload availability
         */
        if (count($request->input('files')) > 5) {
            return $this->respondWithErrors([
                'files' => trans('validations/api/admin/vybe/store.result.error.files.many')
            ]);
        }

        /**
         * Validating files
         */
        $this->vybeService->validateFiles(
            $request->input('files')
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
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem(
            $request->input('showcase_id')
        );

        /**
         * Getting vybe order accept
         */
        $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem(
            $request->input('order_accept_id')
        );

        /**
         * Getting vybe age limit
         */
        $vybeAgeLimitListItem = VybeAgeLimitList::getItem(
            $request->input('age_limit_id')
        );

        /**
         * Getting vybe status
         */
        $vybeStatusListItem = VybeStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Validation completed. Creating
         */

        /**
         * Checking category suggestion existence
         */
        if ($request->input('category_suggestion')) {

            /**
             * Creating category
             */
            $category = $this->categoryRepository->store(
                null,
                $request->input('category_suggestion')
            );
        }

        /**
         * Checking subcategory suggestion existence
         */
        if ($request->input('subcategory_suggestion')) {

            /**
             * Creating subcategory
             */
            $subcategory = $this->categoryRepository->store(
                $category,
                $request->input('subcategory_suggestion')
            );
        }

        /**
         * Checking activity suggestion existence
         */
        if ($request->input('activity_suggestion')) {

            /**
             * Creating activity
             */
            $activity = $this->activityRepository->store(
                $subcategory ?: $category,
                $request->input('activity_suggestion')
            );
        }

        /**
         * Creating vybe
         */
        $vybe = $this->vybeRepository->store(
            $user,
            $activity,
            $vybeTypeListItem,
            $vybePeriodListItem,
            $vybeAccessListItem,
            $request->input('access_password'),
            $vybeShowcaseListItem,
            $vybeStatusListItem,
            $vybeAgeLimitListItem,
            $vybeOrderAcceptListItem,
            $request->input('title'),
            $request->input('user_count'),
            $orderAdvance
        );

        /**
         * Updating vybe step
         */
        $vybe = $this->vybeRepository->updateStep(
            $vybe,
            VybeStepList::getCompleted()
        );

        /**
         * Checking vybe status
         */
        if ($vybe->getStatus()->isSuspended()) {

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateSuspendReason(
                $vybe,
                $request->input('suspend_reason')
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeSuspended(
                $user,
                $vybe
            );
        }

        /**
         * Creating vybe appearance cases
         */
        $this->vybeService->createAppearanceCasesWithoutSuggestions(
            $vybe,
            $request->input('appearance_cases')
        );

        /**
         * Creating vybe schedules
         */
        $this->vybeService->createSchedules(
            $vybe,
            $request->input('schedules')
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
                 * Checking uploaded images existence
                 */
                if ($vybeImageCollection->images->count() > 0) {

                    /**
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        $vybeImageCollection->images
                            ->pluck('id')
                            ->toArray(),
                        null
                    );
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
                $vybeVideoCollection = $this->mediaMicroservice->storeVybeVideos(
                    $fileVideos
                );

                /**
                 * Checking uploaded videos existence
                 */
                if ($vybeVideoCollection->videos->count() > 0) {

                    /**
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        null,
                        $vybeVideoCollection->videos
                            ->pluck('id')
                            ->toArray()
                    );
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

        /**
         * Checking devices existence
         */
        if ($devices) {

            /**
             * Attaching devices to vybe
             */
            $this->vybeRepository->attachDevices(
                $vybe,
                $devices->pluck('id')
                    ->toArray()
            );
        }

        /**
         * Checking device suggestion existence
         */
        if ($request->input('device_suggestion')) {

            /**
             * Creating device
             */
            $device = $this->deviceRepository->store(
                $request->input('device_suggestion')
            );

            /**
             * Checking device existence
             */
            if ($device) {

                /**
                 * Attaching a device to vybe
                 */
                $this->vybeRepository->attachDevice(
                    $vybe,
                    $device
                );
            }
        }

        /**
         * Checking vybe custom settings
         */
        if ($request->input('settings')) {

            /**
             * Updating vybe custom settings
             */
            $this->vybeService->updateVybeCustomSettings(
                $vybe,
                $request->input('settings')
            );
        }

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById(
            $vybe->id
        );

        /**
         * Creating vybe version
         */
        $this->vybeVersionService->create(
            $vybe
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $this->vybeRepository->findFullById(
                    $vybe->id
                ),
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    )
                )
            )['vybe_page'],
            trans('validations/api/admin/vybe/store.result.success')
        );
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
                trans('validations/api/admin/vybe/update.result.error.find.vybe')
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
        if ($category) {
            if ($request->input('category_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/admin/vybe/update.result.error.category.doubling')
                    ]
                ]);
            }
        } elseif (!$request->input('activity_suggestion') &&
            !$vybe->activity
        ) {
            return $this->respondWithErrors([
                'activity_id' => trans('validations/api/admin/vybe/update.result.error.category.required')
            ]);
        }

        /**
         * Getting subcategory
         */
        $subcategory = $this->categoryRepository->findById(
            $request->input('subcategory_id')
        );

        /**
         * Checking possible doubling with a subcategory suggestion
         */
        if ($subcategory) {
            if ($request->input('subcategory_suggestion')) {
                return $this->respondWithErrors([
                    'category_id' => [
                        trans('validations/api/admin/vybe/update.result.error.subcategory.doubling')
                    ]
                ]);
            }
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
        if ($activity) {
            if ($request->input('activity_suggestion')) {
                return $this->respondWithErrors([
                    'activity_id' => trans('validations/api/admin/vybe/update.result.error.activity.doubling')
                ]);
            }
        } elseif (!$request->input('activity_suggestion') &&
            !$vybe->activity
        ) {
            return $this->respondWithErrors([
                'activity_id' => trans('validations/api/admin/vybe/update.result.error.activity.required')
            ]);
        }

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
         * Getting vybe period
         */
        $vybePeriodListItem = VybePeriodList::getItem(
            $request->input('period_id')
        );

        /**
         * Validating 2-nd step
         */
        if ($request->input('appearance_cases')) {

            /**
             * Validating appearance cases
             */
            $this->vybeService->validateAppearanceCases(
                $request->input('appearance_cases')
            );
        }

        /**
         * Validating 3-rd step
         */

        /**
         * Getting expected vybe type
         */
        $vybeTypeListItem = $this->vybeService->getVybeTypeByParameters(
            $vybePeriodListItem ?: $vybe->getPeriod(),
            $request->input('user_count') ? $request->input('user_count') : $vybe->user_count
        );

        /**
         * Checking vybe type
         */
        if (!$vybeTypeListItem->isEvent()) {
            if (!$request->input('order_advance')) {
                return $this->respondWithError(
                    trans('validations/api/admin/vybe/update.order_advance.required')
                );
            }
        }

        /**
         * Getting order advance
         */
        $orderAdvance = !$vybeTypeListItem->isEvent() ?
            $request->input('order_advance') :
            null;

        /**
         * Checking schedules existence
         */
        if ($request->input('schedules')) {

            /**
             * Validating schedules
             */
            $this->vybeService->validateSchedules(
                $vybeTypeListItem,
                $request->input('schedules')
            );
        }

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
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem(
            $request->input('showcase_id')
        );

        /**
         * Getting vybe order accept
         */
        $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem(
            $request->input('order_accept_id')
        );

        /**
         * Getting vybe age limit
         */
        $vybeAgeLimitListItem = VybeAgeLimitList::getItem(
            $request->input('age_limit_id')
        );

        /**
         * Getting vybe status
         */
        $vybeStatusListItem = VybeStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Checking category suggestion existence
         */
        if ($request->input('category_suggestion')) {

            /**
             * Creating category
             */
            $category = $this->categoryRepository->store(
                null,
                $request->input('category_suggestion')
            );
        }

        /**
         * Checking subcategory suggestion existence
         */
        if ($request->input('subcategory_suggestion')) {

            /**
             * Creating subcategory
             */
            $subcategory = $this->categoryRepository->store(
                $category,
                $request->input('subcategory_suggestion')
            );
        }

        /**
         * Checking activity suggestion existence
         */
        if ($request->input('activity_suggestion')) {

            /**
             * Creating activity
             */
            $activity = $this->activityRepository->store(
                $subcategory ?: $category,
                $request->input('activity_suggestion')
            );
        }

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
         * Checking devices existence
         */
        if ($devices) {

            /**
             * Attaching devices to vybe
             */
            $this->vybeRepository->attachDevices(
                $vybe,
                $devices->pluck('id')
                    ->toArray(),
                true
            );
        }

        /**
         * Checking device suggestion existence
         */
        if ($request->input('device_suggestion')) {

            /**
             * Creating device
             */
            $device = $this->deviceRepository->store(
                $request->input('device_suggestion')
            );

            /**
             * Checking device existence
             */
            if ($device) {

                /**
                 * Attaching a device to vybe
                 */
                $this->vybeRepository->attachDevices(
                    $vybe,
                    [$device->id],
                    !$devices
                );
            }
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
                        trans('validations/api/admin/vybe/update.result.error.userCount.max')
                    ]
                ]);
            }
        }

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->update(
            $vybe,
            $activity,
            $vybeTypeListItem,
            $vybePeriodListItem,
            $vybeAccessListItem,
            $vybeShowcaseListItem,
            $vybeStatusListItem,
            $vybeAgeLimitListItem,
            $vybeOrderAcceptListItem,
            $request->input('title'),
            $request->input('user_count'),
            $orderAdvance
        );

        /**
         * Updating vybe access password
         */
        $vybe = $this->vybeRepository->updateAccessPassword(
            $vybe,
            $request->input('access_password')
        );

        /**
         * Checking vybe status
         */
        if ($vybe->getStatus()->isSuspended()) {

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateSuspendReason(
                $vybe,
                $request->input('suspend_reason')
            );

            /**
             * Sending email notification to a vybe owner
             */
            $this->emailNotificationService->sendVybeSuspended(
                $vybe->user,
                $vybe
            );
        }

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateMediaIds(
            $vybe,
            $changedImagesIds,
            $changedVideosIds
        );

        /**
         * Checking appearance cases existence
         */
        if ($request->input('appearance_cases')) {

            /**
             * Updating vybe appearance cases
             */
            $this->vybeService->updateAppearanceCasesWithoutSuggestions(
                $vybe,
                $request->input('appearance_cases')
            );
        }

        /**
         * Checking appearance cases existence
         */
        if ($request->input('schedules')) {

            /**
             * Updating vybe schedules
             */
            $this->vybeService->updateSchedules(
                $vybe,
                $request->input('schedules')
            );
        }

        /**
         * Checking vybe custom settings
         */
        if ($request->input('settings')) {

            /**
             * Updating vybe custom settings
             */
            $this->vybeService->updateVybeCustomSettings(
                $vybe,
                $request->input('settings')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybeStatusListItem->isDraft()) {

            /**
             * Updating vybe
             */
            $this->vybeRepository->updateStep(
                $vybe,
                VybeStepList::getCompleted()
            );
        }

        /**
         * Updating vybe version
         */
        $vybe = $this->vybeRepository->increaseVersion(
            $vybe
        );

        /**
         * Getting updated vybe
         */
        $vybe = $this->vybeRepository->findFullById(
            $vybe->id
        );

        /**
         * Creating vybe version
         */
        $this->vybeVersionService->create(
            $vybe
        );

        return $this->respondWithSuccess(
            $this->transformItem($vybe, new VybePageTransformer(
                $vybe,
                $this->vybeImageRepository->getByVybes(
                    new Collection([$vybe])
                ),
                $this->vybeVideoRepository->getByVybes(
                    new Collection([$vybe])
                )
            ))['vybe_page'],
            trans('validations/api/admin/vybe/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/destroy.result.error.super')
            );
        }

        try {

            /**
             * Checking admin password
             */
            $this->authMicroservice->checkPassword(
                $request->input('password')
            );
        } catch (Exception $exception) {
            throw new ValidationException(
                method_exists($exception, 'getValidationErrors') ?
                    $exception->getValidationErrors()['message'] :
                    trans('validations/api/admin/vybe/destroy.result.error.password'),
                'password'
            );
        }

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/destroy.result.error.find')
            );
        }

        /**
         * Deleting vybe
         */
        $this->vybeService->delete(
            $vybe
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/vybe/destroy.result.success')
        );
    }
}
