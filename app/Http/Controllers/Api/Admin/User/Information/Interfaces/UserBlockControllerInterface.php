<?php

namespace App\Http\Controllers\Api\Admin\User\Information\Interfaces;

use App\Http\Requests\Api\Admin\User\Information\Block\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserBlockControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Information\Interfaces
 */
interface UserBlockControllerInterface
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
     * @param int $blockedUserId
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        int $blockedUserId
    ) : JsonResponse;
}
