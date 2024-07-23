<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category\Interfaces;

use App\Http\Requests\Api\Admin\Csau\Category\Subcategory\StoreRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface SubcategoryControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category\Interfaces
 */
interface SubcategoryControllerInterface
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
}
