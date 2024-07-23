<?php

namespace App\Http\Controllers\Api\Admin\Request\Finance\Interfaces;

use App\Http\Requests\Api\Admin\Request\Finance\BillingChangeRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface BillingChangeRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Request\Finance\Interfaces
 */
interface BillingChangeRequestControllerInterface
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