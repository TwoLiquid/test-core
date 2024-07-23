<?php

namespace App\Http\Controllers\Api\Admin\Csau\Suggestion\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Suggestion\Category\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Suggestion\Category\UpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Suggestion\Interfaces
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
     * @param string $id
     *
     * @return JsonResponse
     */
    public function show(
        string $id
    ) : JsonResponse;

    /**
     * This method provides updating single row
     * by related entity repository
     *
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse;
}
