<?php

namespace App\Http\Controllers\Api\Guest\Activity\Interfaces;

use App\Http\Requests\Api\Guest\Activity\GetByCategoriesRequest;
use App\Http\Requests\Api\Guest\Activity\GetByCategoryRequest;
use App\Http\Requests\Api\Guest\Activity\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface ActivityControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Activity\Interfaces
 */
interface ActivityControllerInterface
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
     * @param GetByCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function getByCategory(
        int $id,
        GetByCategoryRequest $request
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
