<?php

namespace App\Http\Controllers\Api\Admin\Request\User\Interfaces;

use App\Http\Requests\Api\Admin\Request\User\ProfileRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserProfileRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\User\Interfaces
 */
interface UserProfileRequestControllerInterface
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