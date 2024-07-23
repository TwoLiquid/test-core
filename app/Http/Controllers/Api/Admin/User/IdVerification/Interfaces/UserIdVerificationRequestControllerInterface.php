<?php

namespace App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces;

use App\Http\Requests\Api\Admin\User\IdVerification\Request\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UserIdVerificationRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\IdVerification\Interfaces
 */
interface UserIdVerificationRequestControllerInterface
{
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