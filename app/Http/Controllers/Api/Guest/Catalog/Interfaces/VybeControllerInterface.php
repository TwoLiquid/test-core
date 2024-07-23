<?php

namespace App\Http\Controllers\Api\Guest\Catalog\Interfaces;

use App\Http\Requests\Api\Guest\Catalog\Vybe\GetFormRequest;
use App\Http\Requests\Api\Guest\Catalog\Vybe\SearchWithFiltersRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface VybeControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Catalog\Interfaces
 */
interface VybeControllerInterface
{
    /**
     * This method provides getting a single row
     * by related entity repository
     *
     * @param GetFormRequest $request
     *
     * @return JsonResponse
     */
    public function getForm(
        GetFormRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting multiple rows
     * by related entity repository
     *
     * @param SearchWithFiltersRequest $request
     *
     * @return JsonResponse
     */
    public function searchWithFilters(
        SearchWithFiltersRequest $request
    ) : JsonResponse;
}
