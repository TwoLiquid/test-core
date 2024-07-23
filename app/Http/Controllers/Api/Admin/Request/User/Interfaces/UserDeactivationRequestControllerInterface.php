<?php

namespace App\Http\Controllers\Api\Admin\Request\User\Interfaces;

use App\Http\Requests\Api\Admin\Request\User\DeactivationRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserDeactivationRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\User\Interfaces
 */
interface UserDeactivationRequestControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;
}