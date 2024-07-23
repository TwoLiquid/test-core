<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Unsuspend\Interfaces;

use App\Http\Requests\Api\Admin\User\Request\Deletion\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserUnsuspendRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Unsuspend\Interfaces
 */
interface UserUnsuspendRequestControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function index(
        int $id
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
}
