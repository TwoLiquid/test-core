<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Wallet\Withdrawal\StoreRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface WithdrawalControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces
 */
interface WithdrawalControllerInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function closeRequest(
        string $id
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function cancelRequest(
        string $id
    ) : JsonResponse;
}