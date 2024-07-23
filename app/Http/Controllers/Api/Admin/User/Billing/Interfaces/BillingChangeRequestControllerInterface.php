<?php

namespace App\Http\Controllers\Api\Admin\User\Billing\Interfaces;

use App\Http\Requests\Api\Admin\User\Billing\Request\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface BillingChangeRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Billing\Interfaces
 */
interface BillingChangeRequestControllerInterface
{
    /**
     * This method provides getting a single row
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
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse;
}
