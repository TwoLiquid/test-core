<?php

namespace App\Http\Controllers\Api\Admin\General\Setting\Interfaces;

use App\Http\Requests\Api\Admin\General\Setting\User\IndexRequest;
use App\Http\Requests\Api\Admin\General\Setting\User\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserSettingControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\Setting\Interfaces
 */
interface UserSettingControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity aggregator
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating rows
     * by related entity aggregator
     *
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse;
}