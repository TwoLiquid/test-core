<?php

namespace App\Http\Controllers\Api\Admin\General\Setting\Interfaces;

use App\Http\Requests\Api\Admin\General\Setting\General\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface RequestSettingControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\Setting\Interfaces
 */
interface RequestSettingControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

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