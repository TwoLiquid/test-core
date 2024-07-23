<?php

namespace App\Http\Controllers\Api\Guest\Vybe\Interfaces;

use Illuminate\Http\JsonResponse;

/**
 * Interface VybeAgeLimitControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Vybe\Interfaces
 */
interface VybeAgeLimitControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

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
