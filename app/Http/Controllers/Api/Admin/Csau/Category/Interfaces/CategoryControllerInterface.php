<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Category\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Category\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Category\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Category\UpdatePositionsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\UpdateRequest;
use App\Http\Requests\Api\Admin\Csau\Category\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category\Interfaces
 */
interface CategoryControllerInterface
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
     * This method provides getting a single row
     * by related entity repository
     *
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse;

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
     * This method provides updating single row
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
