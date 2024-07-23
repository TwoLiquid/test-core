<?php

namespace App\Http\Controllers\Api\Admin\User\Payout\Interfaces;

use App\Http\Requests\Api\Admin\User\Payout\Method\Request\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PayoutMethodRequestControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Payout\Interfaces
 */
interface PayoutMethodRequestControllerInterface
{
    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse;
}
