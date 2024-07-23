<?php

namespace App\Http\Controllers\Api\Guest\Category\Interfaces;

use App\Http\Requests\Api\Guest\Category\GetByCategoriesRequest;
use App\Http\Requests\Api\Guest\Category\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Category\Interfaces
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
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getByCategory(
        int $id
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @param GetByCategoriesRequest $request
     *
     * @return JsonResponse
     */
    public function getByCategories(
        GetByCategoriesRequest $request
    ) : JsonResponse;
}
