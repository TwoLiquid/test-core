<?php

namespace App\Http\Controllers\Api\Admin\General\Admin\Interfaces;

use App\Http\Requests\Api\Admin\General\Admin\Role\IndexRequest;
use App\Http\Requests\Api\Admin\General\Admin\Role\StoreRequest;
use App\Http\Requests\Api\Admin\General\Admin\Role\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface RoleControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\General\Admin\Interfaces
 */
interface RoleControllerInterface
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
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function getForm() : JsonResponse;

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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(
        int $id
    ) : JsonResponse;
}
