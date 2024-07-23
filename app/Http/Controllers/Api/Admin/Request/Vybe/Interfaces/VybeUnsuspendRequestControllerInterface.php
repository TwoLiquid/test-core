<?php

namespace App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\Request\Vybe\UnsuspendRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeUnsuspendRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\Vybe\Interfaces
 */
interface VybeUnsuspendRequestControllerInterface
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