<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Category\Unit\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\UpdatePositionsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\UpdateRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\StoreRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UnitControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category\Interfaces
 */
interface UnitControllerInterface
{
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
     * This method provides updating single row
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
     * This method provides updating rows
     * by related entity repository
     *
     * @param UpdatePositionsRequest $request
     *
     * @return JsonResponse
     */
    public function updatePositions(
        UpdatePositionsRequest $request
    ) : JsonResponse;

    /**
     * This method provides deleting single row
     * by related entity repository
     *
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse;
}
