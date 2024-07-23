<?php

namespace App\Http\Controllers\Api\Guest\Device;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Device\Interfaces\DeviceControllerInterface;
use App\Http\Requests\Api\Guest\Device\IndexRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\DeviceIconRepository;
use App\Transformers\Api\Guest\Device\DeviceTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class DeviceController
 *
 * @package App\Http\Controllers\Api\Guest\Device
 */
class DeviceController extends BaseController implements DeviceControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceIconRepository
     */
    protected DeviceIconRepository $deviceIconRepository;

    /**
     * DeviceController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceIconRepository deviceIconRepository */
        $this->deviceIconRepository = new DeviceIconRepository();
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
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting devices with pagination
             */
            $devices = $this->deviceRepository->getAllPaginated(
                $request->input('page')
            );

            return $this->setPagination($devices)->respondWithSuccess(
                $this->transformCollection($devices, new DeviceTransformer(
                    $this->deviceIconRepository->getByDevices(
                        new Collection($devices->items())
                    )
                )), trans('validations/api/guest/device/index.result.success')
            );
        }

        /**
         * Getting devices
         */
        $devices = $this->deviceRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($devices, new DeviceTransformer(
                $this->deviceIconRepository->getByDevices(
                    $devices
                )
            )), trans('validations/api/guest/device/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
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
                trans('validations/api/guest/device/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($device, new DeviceTransformer(
                $this->deviceIconRepository->getByDevices(
                    new Collection([$device])
                )
            )), trans('validations/api/guest/device/show.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByActivity(
        int $id
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
                trans('validations/api/guest/platform/getByActivity.result.error.find')
            );
        }

        /**
         * Getting devices
         */
        $devices = $this->deviceRepository->getByActivity(
            $activity
        );

        return $this->respondWithSuccess(
            $this->transformCollection($devices, new DeviceTransformer(
                $this->deviceIconRepository->getByDevices(
                    $devices
                )
            )), trans('validations/api/guest/platform/getByActivity.result.success')
        );
    }
}
