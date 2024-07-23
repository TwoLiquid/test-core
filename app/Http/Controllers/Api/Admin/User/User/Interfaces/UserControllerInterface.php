<?php

namespace App\Http\Controllers\Api\Admin\User\User\Interfaces;

use App\Http\Requests\Api\Admin\User\User\DestroyRequest;
use App\Http\Requests\Api\Admin\User\User\IndexRequest;
use App\Http\Requests\Api\Admin\User\User\ShowRequest;
use App\Http\Requests\Api\Admin\User\User\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\User\Interfaces
 */
interface UserControllerInterface
{
    /**
     * This method provides getting a single row
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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function initializePasswordReset(
        int $id
    ) : JsonResponse;

    /**
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
