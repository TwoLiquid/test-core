<?php

namespace App\Http\Controllers\Api\Admin\Csau\Device;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Device\Interfaces\DeviceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Device\AttachActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Device\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Device\DetachActivityRequest;
use App\Http\Requests\Api\Admin\Csau\Device\GetActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Device\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Device\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Device\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Device\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Device\UpdateRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\DeviceIconRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Device\DeviceService;
use App\Services\File\MediaService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Device\Activity\ActivityTransformer;
use App\Transformers\Api\Admin\Csau\Device\DeviceListTransformer;
use App\Transformers\Api\Admin\Csau\Device\DeviceWithPaginationTransformer;
use App\Transformers\Api\Admin\Csau\Device\DeviceTransformer;
use App\Transformers\Api\Admin\Csau\Device\VybeTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class DeviceController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Device
 */
final class DeviceController extends BaseController implements DeviceControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceService
     */
    protected DeviceService $deviceService;

    /**
     * @var DeviceIconRepository
     */
    protected DeviceIconRepository $deviceIconRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * DeviceController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceService deviceService */
        $this->deviceService = new DeviceService();

        /** @var DeviceIconRepository deviceIconRepository */
        $this->deviceIconRepository = new DeviceIconRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
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
         * Checking pagination enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting devices
             */
            $devices = $this->deviceRepository->getAllPaginated(
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($devices)->respondWithSuccess(
                $this->transformCollection($devices, new DeviceListTransformer(
                    $this->deviceIconRepository->getByDevices(
                        new Collection($devices->items())
                    )
                )), trans('validations/api/admin/csau/device/index.result.success')
            );
        }

        /**
         * Getting devices
         */
        $devices = $this->deviceRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($devices, new DeviceListTransformer(
                $this->deviceIconRepository->getByDevices(
                    $devices
                )
            )), trans('validations/api/admin/csau/device/index.result.success')
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
         * Getting device
         */
        $device = $this->deviceRepository->findFullForAdminById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/show.result.error.find')
            );
        }

        /**
         * Checking pagination is enabled
         */
        if ($request->input('paginated') === true) {
            return $this->respondWithSuccess(
                $this->transformItem(
                    $device,
                    new DeviceWithPaginationTransformer(
                        $this->deviceIconRepository->getByDevices(
                            new Collection([$device])
                        ),
                        $request->input('page'),
                        $request->input('per_page')
                    )
                ), trans('validations/api/admin/csau/device/show.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $device,
                new DeviceTransformer(
                    $this->deviceIconRepository->getByDevices(
                        new Collection([$device])
                    )
                )
            ), trans('validations/api/admin/csau/device/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getVybes(
        int $id,
        GetVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/getVybes.result.error.find')
            );
        }

        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getByDevicePaginated(
            $device,
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer
            ), trans('validations/api/admin/csau/device/getVybes.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getActivities(
        int $id,
        GetActivitiesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/getActivities.result.error.find')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByDevicePaginated(
            $device,
            $request->input('search'),
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($activities)->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection($activities->items())
                    )
                )
            ), trans('validations/api/admin/csau/device/getActivities.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            /**
             * Validating device icon
             */
            $this->mediaService->validateDeviceIcon(
                $request->input('icon.content'),
                $request->input('icon.mime')
            );
        }

        /**
         * Creating device
         */
        $device = $this->deviceRepository->store(
            $request->input('name'),
            $request->input('visible')
        );

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/store.result.error.create')
            );
        }

        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Uploading device icon
                 */
                $this->mediaMicroservice->storeDeviceIcon(
                    $device,
                    $request->input('icon.content'),
                    $request->input('icon.extension'),
                    $request->input('icon.mime')
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
                $device,
                new DeviceTransformer(
                    $this->deviceIconRepository->getByDevices(
                        new Collection($device)
                    )
                )
            ), trans('validations/api/admin/csau/device/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/update.result.error.find')
            );
        }

        /**
         * Updating device
         */
        $device = $this->deviceRepository->update(
            $device,
            $request->input('name'),
            $request->input('visible')
        );

        /**
         * Checking uploaded icon exists
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Deleting device icons
                 */
                $this->mediaMicroservice->deleteDeviceIcons(
                    $device
                );
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
                 * Uploading device icon
                 */
                $this->mediaMicroservice->storeDeviceIcon(
                    $device,
                    $request->input('icon.content'),
                    $request->input('icon.extension'),
                    $request->input('icon.mime')
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
                $device,
                new DeviceTransformer(
                    $this->deviceIconRepository->getByDevices(
                        new Collection([$device])
                    )
                )
            ), trans('validations/api/admin/csau/device/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param AttachActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachActivities(
        int $id,
        AttachActivitiesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/attachActivities.result.error.find')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByIds(
            $request->input('activities_ids')
        );

        /**
         * Attaching activities to a device
         */
        $this->deviceRepository->attachActivities(
            $device,
            $activities->pluck('id')
                ->values()
                ->toArray()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/device/attachActivities.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $activityId
     * @param DetachActivityRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachActivity(
        int $id,
        int $activityId,
        DetachActivityRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/detachActivity.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/detachActivity.result.error.find.device')
            );
        }

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $activityId
        );

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/detachActivity.result.error.find.activity')
            );
        }

        /**
         * Detaching activity from a device
         */
        $this->deviceRepository->detachActivity(
            $device,
            $activity
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/device/detachActivity.result.success')
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
                trans('validations/api/admin/csau/device/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting device
         */
        $device = $this->deviceRepository->findById($id);

        /**
         * Checking device existence
         */
        if (!$device) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/device/destroy.result.error.find')
            );
        }

        /**
         * Checking a device already has vybes
         */
        if ($device->vybes->count()) {
            return $this->respondWithErrors([
                'vybes' => trans('validations/api/admin/csau/device/destroy.result.error.vybes')
            ]);
        }

        try {

            /**
             * Deleting device icons
             */
            $this->mediaMicroservice->deleteDeviceIcons(
                $device
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
         * Deleting device
         */
        $this->deviceRepository->delete(
            $device
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/device/destroy.result.success')
        );
    }
}
