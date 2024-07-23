<?php

namespace App\Http\Controllers\Api\General\Tip\Interfaces;

use App\Http\Requests\Api\General\Tip\CancelPaymentRequest;
use App\Http\Requests\Api\General\Tip\ExecutePaymentRequest;
use App\Http\Requests\Api\General\Tip\IndexRequest;
use App\Http\Requests\Api\General\Tip\StoreRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface TipControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Tip\Interfaces
 */
interface TipControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing a single row
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
