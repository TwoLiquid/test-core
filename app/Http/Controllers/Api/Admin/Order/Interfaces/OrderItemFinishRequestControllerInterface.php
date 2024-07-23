<?php

namespace App\Http\Controllers\Api\Admin\Order\Interfaces;

use App\Http\Requests\Api\Admin\Order\OrderItem\FinishRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface OrderItemFinishRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Order\Interfaces
 */
interface OrderItemFinishRequestControllerInterface
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
}