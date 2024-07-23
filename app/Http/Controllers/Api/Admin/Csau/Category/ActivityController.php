<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Category\Interfaces\ActivityControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\AttachActivityTagsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\AttachDevicesRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\AttachPlatformsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\AttachUnitsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\DetachDeviceRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\DetachPlatformRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\DetachUnitRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\DetachTagRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\UpdatePositionsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Activity\UpdateRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Activity\ActivityTagRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\Category\CategoryService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Category\ActivityTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class ActivityController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category
 */
final class ActivityController extends BaseController implements ActivityControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var ActivityTagRepository
     */
    protected ActivityTagRepository $activityTagRepository;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * ActivityController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var ActivityTagRepository activityTagRepository */
        $this->activityTagRepository = new ActivityTagRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryService categoryService */
        $this->categoryService = new CategoryService();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Checking subcategory existence
         */
        if ($request->input('subcategory_id')) {

            /**
             * Getting subcategory
             */
            $category = $this->categoryRepository->findById(
                $request->input('subcategory_id')
            );
        }

        /**
         * Creating activity
         */
        $activity = $this->activityRepository->store(
            $category,
            $request->input('name'),
            $request->input('visible')
        );

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/store.result.error.create')
            );
        }

        /**
         * Checking category
         */
        if ($this->categoryService->isVideoGames(
            $category
        )) {

            /**
             * Getting devices
             */
            $devices = $this->deviceRepository->getByIds(
                $request->input('devices_ids')
            );

            /**
             * Attaching devices to activity
             */
            $this->activityRepository->attachDevices(
                $activity,
                $devices->pluck('id')
                    ->values()
                    ->toArray()
            );
        }

        /**
         * Getting platforms
         */
        $platforms = $this->platformRepository->getByIds(
            $request->input('platforms_ids')
        );

        /**
         * Attaching platforms to activity
         */
        $this->activityRepository->attachPlatforms(
            $activity,
            $platforms->pluck('id')
                ->values()
                ->toArray()
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $activity,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection([$activity])
                    )
                )
            ), trans('validations/api/admin/csau/category/activity/store.result.success')
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
     * @throws GuzzleException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/update.result.error.find')
            );
        }

        /**
         * Updating activity
         */
        $activity = $this->activityRepository->update(
            $activity,
            null,
            $request->input('name'),
            $request->input('visible'),
            null
        );

        if ($request->input('activity_images')) {

            /**
             * Validating activity images
             */
            $this->activityService->validateActivityImages(
                $request->input('activity_images')
            );

            try {

                /**
                 * Uploading activity images
                 */
                $this->mediaMicroservice->storeActivityImages(
                    $activity,
                    $request->input('activity_images')
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

        return $this->respondWithSuccess(
            $this->transformItem(
                $activity,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection([$activity])
                    )
                )
            ), trans('validations/api/admin/csau/category/activity/update.result.success')
        );
    }

    /**
     * @param UpdatePositionsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        UpdatePositionsRequest $request
    ) : JsonResponse
    {
        /**
         * Updating categories positions
         */
        $activities = $this->activityService->updatePositions(
            $request->input('activities_items')
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/admin/csau/category/activity/updatePositions.result.success')
        );
    }

    /**
     * @param AttachActivityTagsRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function attachActivityTags(
        AttachActivityTagsRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByIds(
            $request->input('activities_ids')
        );

        /**
         * Getting activity tags
         */
        $activityTags = $this->activityTagRepository->getByIds(
            $request->input('activity_tags_ids')
        );

        /**
         * Attaching activity tags to activities
         */
        $activities = $this->activityService->attachActivityTags(
            $activities,
            $activityTags
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/admin/csau/category/activity/attachActivityTags.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $activityTagId
     * @param DetachTagRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachActivityTag(
        int $id,
        int $activityTagId,
        DetachTagRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachActivityTag.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachActivityTag.result.error.find.activity')
            );
        }

        /**
         * Getting activity tag
         */
        $activityTag = $this->activityTagRepository->findById($activityTagId);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachActivityTag.result.error.find.activityTag')
            );
        }

        /**
         * Detaching activity tag from activity
         */
        $this->activityRepository->detachActivityTag(
            $activity,
            $activityTag
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/detachActivityTag.result.success')
        );
    }

    /**
     * @param int $id
     * @param AttachUnitsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachUnits(
        int $id,
        AttachUnitsRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/attachUnit.result.error.find.activity')
            );
        }

        /**
         * Getting unit
         */
        $units = $this->unitRepository->getByIds(
            $request->input('units_ids')
        );

        /**
         * Attaching unit to activity
         */
        $this->activityRepository->attachUnits(
            $activity,
            buildUnitsForSyncWithVisible(
                $units->pluck('id')
                    ->values()
                    ->toArray(),
                $request->input('visible')
            )
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/attachUnit.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $unitId
     * @param DetachUnitRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachUnit(
        int $id,
        int $unitId,
        DetachUnitRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachUnit.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachUnit.result.error.find.activity')
            );
        }

        /**
         * Getting unit
         */
        $unit = $this->unitRepository->findById($unitId);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachUnit.result.error.find.unit')
            );
        }

        /**
         * Detaching unit from activity
         */
        $this->activityRepository->detachUnit(
            $activity,
            $unit
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/detachUnit.result.success')
        );
    }

    /**
     * @param int $id
     * @param AttachDevicesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachDevices(
        int $id,
        AttachDevicesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/attachDevices.result.error.find')
            );
        }

        /**
         * Getting devices
         */
        $devices = $this->deviceRepository->getByIds(
            $request->input('devices_ids')
        );

        /**
         * Attaching devices to activity
         */
        $this->activityRepository->attachDevices(
            $activity,
            $devices->pluck('id')
                ->values()
                ->toArray(),
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/attachDevices.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $deviceId
     * @param DetachDeviceRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachDevice(
        int $id,
        int $deviceId,
        DetachDeviceRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachDevice.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachDevice.result.error.find.activity')
            );
        }

        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById(
            $deviceId
        );

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachDevice.result.error.find.device')
            );
        }

        /**
         * Detaching a device from activity
         */
        $this->activityRepository->detachDevice(
            $activity,
            $device
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/detachDevice.result.success')
        );
    }

    /**
     * @param int $id
     * @param AttachPlatformsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachPlatforms(
        int $id,
        AttachPlatformsRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/attachPlatforms.result.error.find')
            );
        }

        /**
         * Getting platforms
         */
        $platforms = $this->platformRepository->getByIds(
            $request->input('platforms_ids')
        );

        /**
         * Attaching platforms to activity
         */
        $this->activityRepository->attachPlatforms(
            $activity,
            $platforms->pluck('id')
                ->values()
                ->toArray(),
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/attachPlatforms.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $platformId
     * @param DetachPlatformRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachPlatform(
        int $id,
        int $platformId,
        DetachPlatformRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachPlatform.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachPlatform.result.error.find.activity')
            );
        }

        /**
         * Getting platform
         */
        $platform = $this->platformRepository->findById(
            $platformId
        );

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/detachPlatform.result.error.find.platform')
            );
        }

        /**
         * Detaching a platform from activity
         */
        $this->activityRepository->detachPlatform(
            $activity,
            $platform
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/detachPlatform.result.success')
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
     * @throws MicroserviceException
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
                trans('validations/api/admin/csau/category/activity/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/destroy.result.error.find')
            );
        }

        /**
         * Checking activity already has vybes
         */
        if ($activity->vybes->count()) {
            return $this->respondWithErrors([
                'vybes' => trans('validations/api/admin/csau/category/activity/destroy.result.error.vybes')
            ]);
        }

        try {

            /**
             * Deleting activity images
             */
            $this->mediaMicroservice->deleteForActivity(
                $activity
            );
        } catch (Exception $exception) {

            /**
             * Adding background error to controller stack
             */
            $this->addBackgroundError(
                $exception
            );
        }

        /**
         * Deleting activity
         */
        if (!$this->activityRepository->delete($activity)) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/activity/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/activity/destroy.result.success')
        );
    }
}
