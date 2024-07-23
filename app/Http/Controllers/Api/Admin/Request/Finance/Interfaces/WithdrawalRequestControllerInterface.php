<?php

namespace App\Http\Controllers\Api\Admin\Request\Finance\Interfaces;

use App\Http\Requests\Api\Admin\Request\Finance\WithdrawalRequest\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface WithdrawalRequestControllerInterface
 * 
 * @package App\Http\Controllers\Api\Admin\Request\Finance\Interfaces
 */
interface WithdrawalRequestControllerInterface
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