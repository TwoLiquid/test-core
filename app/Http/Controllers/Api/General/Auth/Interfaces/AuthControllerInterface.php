<?php

namespace App\Http\Controllers\Api\General\Auth\Interfaces;

use App\Http\Requests\Api\General\Auth\AttachFavoriteActivitiesRequest;
use App\Http\Requests\Api\General\Auth\DetachFavoriteActivitiesRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AuthControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Auth\Interfaces
 */
interface AuthControllerInterface
{
    /**
     * This method provides getting data
     * by related entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function getAuthUser() : JsonResponse;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $subscriptionId
     *
     * @return JsonResponse
     */
    public function attachSubscription(
        int $subscriptionId
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $subscriptionId
     *
     * @return JsonResponse
     */
    public function detachSubscription(
        int $subscriptionId
    ) : JsonResponse;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function attachFavoriteVybe(
        int $id
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function detachFavoriteVybe(
        int $id
    ) : JsonResponse;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function attachFavoriteActivity(
        int $id
    ) : JsonResponse;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param AttachFavoriteActivitiesRequest $request
     *
     * @return JsonResponse
     */
    public function attachFavoriteActivities(
        AttachFavoriteActivitiesRequest $request
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function detachFavoriteActivity(
        int $id
    ) : JsonResponse;

    /**
     * This method provides detaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param DetachFavoriteActivitiesRequest $request
     *
     * @return JsonResponse
     */
    public function detachFavoriteActivities(
        DetachFavoriteActivitiesRequest $request
    ) : JsonResponse;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $blockedUserId
     *
     * @return JsonResponse
     */
    public function attachBlockedUser(
        int $blockedUserId
    ) : JsonResponse;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param int $blockedUserId
     *
     * @return JsonResponse
     */
    public function detachBlockedUser(
        int $blockedUserId
    ) : JsonResponse;

    /**
     * This method provides action by related
     * entity repository with a certain query
     *
     * @return JsonResponse
     */
    public function logout() : JsonResponse;
}
