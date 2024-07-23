<?php

namespace App\Http\Controllers\Api\Guest\Search\Interfaces;

use App\Http\Requests\Api\Guest\Search\GlobalSearchRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface SearchControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Search\Interfaces
 */
interface SearchControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param GlobalSearchRequest $request
     *
     * @return JsonResponse
     */
    public function globalSearch(
        GlobalSearchRequest $request
    ) : JsonResponse;
}