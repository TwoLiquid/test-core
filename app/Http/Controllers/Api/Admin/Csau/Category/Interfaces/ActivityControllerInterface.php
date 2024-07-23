<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category\Interfaces;

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
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category\Interfaces
 */
interface ActivityControllerInterface
{
    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating rows
     * by related entity repository
     *
     * @param UpdatePositionsRequest $request
     *
     * @return JsonResponse
     */
    public function updatePositions(
        UpdatePositionsRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param AttachActivityTagsRequest $request
     *
     * @return JsonResponse
     */
    public function attachActivityTags(
        AttachActivityTagsRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param int $activityTagId
     * @param DetachTagRequest $request
     *
     * @return JsonResponse
     */
    public function detachActivityTag(
        int $id,
        int $activityTagId,
        DetachTagRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param AttachUnitsRequest $request
     *
     * @return JsonResponse
     */
    public function attachUnits(
        int $id,
        AttachUnitsRequest $request
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param int $unitId
     * @param DetachUnitRequest $request
     *
     * @return JsonResponse
     */
    public function detachUnit(
        int $id,
        int $unitId,
        DetachUnitRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param AttachDevicesRequest $request
     *
     * @return JsonResponse
     */
    public function attachDevices(
        int $id,
        AttachDevicesRequest $request
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param int $deviceId
     * @param DetachDeviceRequest $request
     *
     * @return JsonResponse
     */
    public function detachDevice(
        int $id,
        int $deviceId,
        DetachDeviceRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param AttachPlatformsRequest $request
     *
     * @return JsonResponse
     */
    public function attachPlatforms(
        int $id,
        AttachPlatformsRequest $request
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param int $platformId
     * @param DetachPlatformRequest $request
     *
     * @return JsonResponse
     */
    public function detachPlatform(
        int $id,
        int $platformId,
        DetachPlatformRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse;
}
