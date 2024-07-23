<?php

namespace App\Http\Controllers\Api\Admin\User\Payout\Interfaces;

use App\Http\Requests\Api\Admin\User\Payout\Method\StoreRequest;
use App\Http\Requests\Api\Admin\User\Payout\Method\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PayoutMethodControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\User\Payout\Interfaces
 */
interface PayoutMethodControllerInterface
{
    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function index(
        int $id
    ) : JsonResponse;

    /**
     * This method provides storing data
     * by related entity repository
     *
     * @param int $id
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(
        int $id,
        StoreRequest $request
    ) : JsonResponse;

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

    /**
     * This method provides deleting data
     * by related entity repository
     *
     * @param int $id
     * @param int $methodId
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        int $methodId
    ) : JsonResponse;
}
