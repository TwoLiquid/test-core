<?php

namespace App\Http\Controllers\Api\Guest\Catalog\Interfaces;

use App\Http\Requests\Api\Guest\Catalog\Category\GetByCodeRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Category\Interfaces
 */
interface CategoryControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param GetByCodeRequest $request
     * @return JsonResponse
     */
    public function getByCode(
        GetByCodeRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting rows
     * by related entity repository
     *
     * @return JsonResponse
     */
    public function getCategoriesForNavbar() : JsonResponse;
}
