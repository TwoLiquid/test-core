<?php

namespace App\Http\Controllers\Api\General\Place\Interfaces;

use App\Http\Requests\Api\General\Place\AutocompleteCityRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PlaceControllerInterface
 *
 * @package App\Http\Controllers\Api\General\Place\Interfaces
 */
interface PlaceControllerInterface
{
    /**
     * This method provides getting data
     *
     * @param AutocompleteCityRequest $request
     *
     * @return JsonResponse
     */
    public function autocompleteCity(
        AutocompleteCityRequest $request
    ) : JsonResponse;
}