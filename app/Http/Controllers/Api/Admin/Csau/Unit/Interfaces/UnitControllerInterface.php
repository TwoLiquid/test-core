<?php

namespace App\Http\Controllers\Api\Admin\Csau\Unit\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Unit\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\UpdateRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface UnitControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Unit\Interfaces
 */
interface UnitControllerInterface
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
     * This method provides getting rows
     * by related entity repository
     *
     * @param int $id
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     */
    public function getVybes(
        int $id,
        GetVybesRequest $request
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
