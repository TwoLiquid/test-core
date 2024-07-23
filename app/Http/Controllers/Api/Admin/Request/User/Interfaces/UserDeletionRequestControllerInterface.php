<?php

namespace App\Http\Controllers\Api\Admin\Request\User\Interfaces;

use App\Http\Requests\Api\Admin\Request\User\DeletionRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserDeletionRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\User\Interfaces
 */
interface UserDeletionRequestControllerInterface
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