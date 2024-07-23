<?php

namespace App\Http\Controllers\Api\Guest\Activity\Interfaces;

use App\Http\Requests\Api\Guest\Activity\Tag\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityTagControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Activity\Interfaces
 */
interface ActivityTagControllerInterface
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

    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse;
}
