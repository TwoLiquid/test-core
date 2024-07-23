<?php

namespace App\Http\Controllers\Api\Guest\Search\Interfaces;

use App\Http\Requests\Api\Guest\Search\User\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserControllerInterface
 *
 * @package App\Http\Controllers\Api\Main\General\User\Interfaces
 */
interface UserControllerInterface
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