<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces;

use App\Http\Requests\Api\General\Dashboard\Wallet\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface WalletControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces
 */
interface WalletControllerInterface
{
    /**
     * This method provides getting rows
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