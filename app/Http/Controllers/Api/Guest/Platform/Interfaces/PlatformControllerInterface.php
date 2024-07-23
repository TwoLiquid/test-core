<?php

namespace App\Http\Controllers\Api\Guest\Platform\Interfaces;

use App\Http\Requests\Api\Guest\Platform\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PlatformControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Platform\Interfaces
 */
interface PlatformControllerInterface
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

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getByActivity(
        int $id
    ) : JsonResponse;
}
