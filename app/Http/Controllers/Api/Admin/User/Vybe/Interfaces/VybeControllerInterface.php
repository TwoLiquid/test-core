<?php

namespace App\Http\Controllers\Api\Admin\User\Vybe\Interfaces;

use App\Http\Requests\Api\Admin\User\Vybe\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Vybe\Interfaces
 */
interface VybeControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse;
}
