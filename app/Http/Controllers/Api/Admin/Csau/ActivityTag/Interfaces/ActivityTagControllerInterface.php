<?php

namespace App\Http\Controllers\Api\Admin\Csau\ActivityTag\Interfaces;

use App\Http\Requests\Api\Admin\Csau\ActivityTag\AttachActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\DetachActivityRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityTagControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\ActivityTag\Interfaces
 */
interface ActivityTagControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
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
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param AttachActivitiesRequest $request
     *
     * @return JsonResponse
     */
    public function attachActivities(
        int $id,
        AttachActivitiesRequest $request
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     * @param int $activityId
     * @param DetachActivityRequest $request
     *
     * @return JsonResponse
     */
    public function detachActivity(
        int $id,
        int $activityId,
        DetachActivityRequest $request
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
