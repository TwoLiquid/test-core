<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Deletion\Interfaces;

use App\Http\Requests\Api\Admin\User\Request\Deletion\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserDeletionRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Deletion\Interfaces
 */
interface UserDeletionRequestControllerInterface
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
