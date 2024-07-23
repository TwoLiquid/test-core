<?php

namespace App\Http\Controllers\Api\Admin\Csau\Platform\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Device\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\AttachActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\DetachActivityRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\GetActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\UpdateRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\ShowRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PlatformControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Platform\Interfaces
 */
interface PlatformControllerInterface
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
     * @param ShowRequest $request
     *
     * @return JsonResponse
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getVybes(
        int $id,
        GetVybesRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetActivitiesRequest $request
     *
     * @return JsonResponse
     */
    public function getActivities(
        int $id,
        GetActivitiesRequest $request
    ) : JsonResponse;

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
     * This method provides attaching existing rows by related
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