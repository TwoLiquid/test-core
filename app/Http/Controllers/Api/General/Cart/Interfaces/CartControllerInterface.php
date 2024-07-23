<?php

namespace App\Http\Controllers\Api\General\Cart\Interfaces;

use App\Http\Requests\Api\General\Cart\CheckoutCancelRequest;
use App\Http\Requests\Api\General\Cart\CheckoutExecuteRequest;
use App\Http\Requests\Api\General\Cart\CheckoutRequest;
use App\Http\Requests\Api\General\Cart\IndexRequest;
use App\Http\Requests\Api\General\Cart\RefreshRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CartControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Cart\Interfaces
 */
interface CartControllerInterface
{
    /**
     * This method provides getting all rows
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
     * This method provides updating rows
     * by related entity repository
     *
     * @param RefreshRequest $request
     *
     * @return JsonResponse
     */
    public function refresh(
        RefreshRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository
     *
     * @param CheckoutRequest $request
     *
     * @return JsonResponse
     */
    public function checkout(
        CheckoutRequest $request
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository
     *
     * @param CheckoutExecuteRequest $request
     *
     * @return JsonResponse
     */
    public function checkoutExecute(
        CheckoutExecuteRequest $request
    ) : JsonResponse;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param CheckoutCancelRequest $request
     *
     * @return JsonResponse
     */
    public function checkoutCancel(
        CheckoutCancelRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id
    ) : JsonResponse;
}
