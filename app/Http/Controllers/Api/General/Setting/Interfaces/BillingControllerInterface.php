<?php

namespace App\Http\Controllers\Api\General\Setting\Interfaces;

use App\Http\Requests\Api\General\Setting\Billing\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface BillingControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Setting\Interfaces
 */
interface BillingControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

    /**
     * This method provides storing a single row
     * by related entity repository
     *
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function close() : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function destroy() : JsonResponse;
}
