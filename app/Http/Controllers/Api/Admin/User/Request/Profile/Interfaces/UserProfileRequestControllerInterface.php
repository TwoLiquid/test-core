<?php

namespace App\Http\Controllers\Api\Admin\User\Request\Profile\Interfaces;

use App\Http\Requests\Api\Admin\User\Request\Profile\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserProfileRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Request\Profile\Interfaces
 */
interface UserProfileRequestControllerInterface
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
     * This method provides updating data
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
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function acceptAll(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function declineAll(
        int $id
    ) : JsonResponse;
}
