<?php

namespace App\Http\Controllers\Api\General\Setting\Interfaces;

use App\Http\Requests\Api\General\Setting\PayoutMethod\StoreRequest;
use App\Http\Requests\Api\General\Setting\PayoutMethod\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PayoutMethodControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Setting\Interfaces
 */
interface PayoutMethodControllerInterface
{
    /**
     * This method provides getting rows
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
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function cancelRequest(
        int $id
    ) : JsonResponse;

    /**
     * This method provides deleting single row
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
