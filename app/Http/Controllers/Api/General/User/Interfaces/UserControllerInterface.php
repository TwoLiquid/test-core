<?php

namespace App\Http\Controllers\Api\General\User\Interfaces;

use App\Http\Requests\Api\General\User\GetUserSubscribersRequest;
use App\Http\Requests\Api\General\User\GetUserSubscriptionsRequest;
use App\Http\Requests\Api\General\User\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserControllerInterface
 *
 * @package App\Http\Controllers\Api\General\User\Interfaces
 */
interface UserControllerInterface
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
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetUserSubscriptionsRequest $request
     *
     * @return JsonResponse
     */
    public function getUserSubscriptions(
        int $id,
        GetUserSubscriptionsRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetUserSubscribersRequest $request
     *
     * @return JsonResponse
     */
    public function getUserSubscribers(
        int $id,
        GetUserSubscribersRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository with a certain query
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function storeVisit(
        int $id
    ) : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id
    ) : JsonResponse;
}
