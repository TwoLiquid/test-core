<?php

namespace App\Http\Controllers\Api\General\Profile\Interfaces;

use App\Http\Requests\Api\General\Profile\Vybe\ShowRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Profile\Interfaces
 */
interface VybeControllerInterface
{
    /**
     * This method provides checking data
     * by related entity repository
     *
     * @param ShowRequest $request
     * @param string $username
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        ShowRequest $request,
        string $username,
        int $id
    ) : JsonResponse;
}