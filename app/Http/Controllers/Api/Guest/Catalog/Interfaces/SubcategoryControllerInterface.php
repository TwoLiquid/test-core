<?php

namespace App\Http\Controllers\Api\Guest\Catalog\Interfaces;

use App\Http\Requests\Api\Guest\Catalog\Subcategory\GetByCodeRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface CategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Category\Interfaces
 */
interface SubcategoryControllerInterface
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
}
