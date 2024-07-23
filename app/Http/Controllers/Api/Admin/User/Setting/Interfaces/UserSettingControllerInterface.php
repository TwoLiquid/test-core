<?php

namespace App\Http\Controllers\Api\Admin\User\Setting\Interfaces;

use App\Http\Requests\Api\Admin\User\Setting\IndexRequest;
use App\Http\Requests\Api\Admin\User\Setting\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserSettingControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Setting\Interfaces
 */
interface UserSettingControllerInterface
{
    /**
     * This method provides getting rows
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
     * This method provides updating rows
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
