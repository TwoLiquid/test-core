<?php

namespace App\Http\Controllers\Api\Admin\Invoice\Interfaces;

use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Request\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface WithdrawalRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Invoice\Interfaces
 */
interface WithdrawalRequestControllerInterface
{
    /**
     * This method provides getting row
     * by related entity repository
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function index(
        string $id
    ) : JsonResponse;

    /**
     * This method provides updating row
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

    /**
     * This method provides sending data
     * by related entity repository
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function resendEmail(
        string $id
    ) : JsonResponse;
}