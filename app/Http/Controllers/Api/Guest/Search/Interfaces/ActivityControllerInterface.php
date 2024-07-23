<?php

namespace App\Http\Controllers\Api\Guest\Search\Interfaces;

use App\Http\Requests\Api\Guest\Activity\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Search\Interfaces
 */
interface ActivityControllerInterface
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