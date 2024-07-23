<?php

namespace App\Http\Controllers\Api\Guest\Device\Interfaces;

use App\Http\Requests\Api\Guest\Device\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface DeviceControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Device\Interfaces
 */
interface DeviceControllerInterface
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
