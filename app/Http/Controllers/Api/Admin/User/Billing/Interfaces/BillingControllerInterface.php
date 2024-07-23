<?php

namespace App\Http\Controllers\Api\Admin\User\Billing\Interfaces;

use App\Http\Requests\Api\Admin\User\Billing\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface BillingControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Billing\Interfaces
 */
interface BillingControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function index(
        int $userId
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