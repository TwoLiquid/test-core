<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\CancelPaymentRequest;
use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\StoreRequest;
use App\Http\Requests\Api\General\Dashboard\Wallet\AddFunds\ExecutePaymentRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface AddFundsControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces
 */
interface AddFundsControllerInterface
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
     * This method provides storing data
     * by related entity repository
     *
     * @param int $id
     * @param ExecutePaymentRequest $request
     *
     * @return JsonResponse
     */
    public function executePayment(
        int $id,
        ExecutePaymentRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     * @param CancelPaymentRequest $request
     *
     * @return JsonResponse
     */
    public function cancelPayment(
        int $id,
        CancelPaymentRequest $request
    ) : JsonResponse;
}