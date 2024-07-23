<?php

namespace App\Http\Controllers\Api\Guest\Place\Interfaces;

use App\Http\Requests\Api\Guest\Place\Region\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface RegionPlaceControllerInterface
 *
 * @package App\Http\Controllers\Api\Guest\Place\Interfaces
 */
interface RegionPlaceControllerInterface
{
    /**
     * This method provides getting all rows
     * by related entity repository
     *
     * @param string $countryPlaceId
     * @param IndexRequest $request
     *
     * @return JsonResponse
     */
    public function index(
        string $countryPlaceId,
        IndexRequest $request
    ) : JsonResponse;
}