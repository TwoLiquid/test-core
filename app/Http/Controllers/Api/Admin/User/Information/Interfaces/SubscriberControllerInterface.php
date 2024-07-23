<?php

namespace App\Http\Controllers\Api\Admin\User\Information\Interfaces;

use App\Http\Requests\Api\Admin\User\Information\Subscription\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface SubscriberControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Information\Interfaces
 */
interface SubscriberControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting a single row
     * by related entity repository
     *
     * @param int $id
     * @param int $subscriberId
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        int $subscriberId
    ) : JsonResponse;
}
