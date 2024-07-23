<?php

namespace App\Http\Controllers\Api\Admin\User\Information\Interfaces;

use App\Http\Requests\Api\Admin\User\Information\Subscription\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface SubscriptionControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Information\Interfaces
 */
interface SubscriptionControllerInterface
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
}
