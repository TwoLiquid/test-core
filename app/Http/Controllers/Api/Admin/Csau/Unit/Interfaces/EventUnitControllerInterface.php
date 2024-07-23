<?php

namespace App\Http\Controllers\Api\Admin\Csau\Unit\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Unit\Event\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\Event\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\Event\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\Event\UpdateRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\Event\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\Event\ShowRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface EventUnitControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Unit\Interfaces
 */
interface EventUnitControllerInterface
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
