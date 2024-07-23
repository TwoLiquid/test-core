<?php

namespace App\Http\Controllers\Api\Admin\Place\Interfaces;

use App\Http\Requests\Api\Admin\Place\AutocompleteCityRequest;
use App\Http\Requests\Api\Admin\Place\AutocompleteCountryRequest;
use App\Http\Requests\Api\Admin\Place\AutocompleteRegionRequest;
use Illuminate\Http\JsonResponse;

/**
 * Interface PlaceControllerInterface
 *
 * @package App\Http\Controllers\Api\Admin\Place\Interfaces
 */
interface PlaceControllerInterface
{
    /**
     * This method provides getting data
     *
     * @param AutocompleteCountryRequest $request
     *
     * @return JsonResponse
     */
    public function autocompleteCountry(
        AutocompleteCountryRequest $request
    ) : JsonResponse;

    /**
     * This method provides getting data
     *
     * @param AutocompleteRegionRequest $request
     *
     * @return JsonResponse
     */
    public function autocompleteRegion(
        AutocompleteRegionRequest $request
    ) : JsonResponse;

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