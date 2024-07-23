<?php

namespace App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces;

use App\Http\Requests\Api\Admin\User\IdVerification\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserIdVerificationControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces
 */
interface UserIdVerificationControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function index(
        int $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse;
}