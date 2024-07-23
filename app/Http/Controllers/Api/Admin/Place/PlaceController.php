<?php

namespace App\Http\Controllers\Api\Admin\Place;

use App\Exceptions\BaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\Place\Interfaces\PlaceControllerInterface;
use App\Http\Requests\Api\Admin\Place\AutocompleteCityRequest;
use App\Http\Requests\Api\Admin\Place\AutocompleteCountryRequest;
use App\Http\Requests\Api\Admin\Place\AutocompleteRegionRequest;
use App\Lists\Language\LanguageList;
use App\Services\Google\GooglePlaceService;
use App\Transformers\Api\Admin\Place\PredictionCityTransformer;
use App\Transformers\Api\Admin\Place\PredictionCountryTransformer;
use App\Transformers\Api\Admin\Place\PredictionRegionTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PlaceController
 *
 * @package App\Http\Controllers\Api\Admin\Place
 */
class PlaceController extends BaseController implements PlaceControllerInterface
{
    /**
     * @var GooglePlaceService
     */
    protected GooglePlaceService $googlePlaceService;

    /**
     * PlaceController constructor
     */
    public function __construct()
    {
        /** @var GooglePlaceService googlePlaceService */
        $this->googlePlaceService = new GooglePlaceService();
    }

    /**
     * @param AutocompleteCountryRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     */
    public function autocompleteCountry(
        AutocompleteCountryRequest $request
    ) : JsonResponse
    {
        /**
         * Getting autocomplete country predictions
         */
        $predictions = $this->googlePlaceService->autocompleteCountry(
            LanguageList::getEnglish(),
            $request->input('search')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($predictions, new PredictionCountryTransformer),
            trans('validations/api/admin/place/autocompleteCountry.result.success')
        );
    }

    /**
     * @param AutocompleteRegionRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     */
    public function autocompleteRegion(
        AutocompleteRegionRequest $request
    ) : JsonResponse
    {
        /**
         * Getting autocomplete region predictions
         */
        $predictions = $this->googlePlaceService->autocompleteRegion(
            LanguageList::getEnglish(),
            $request->input('country_code'),
            $request->input('search')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($predictions, new PredictionRegionTransformer),
            trans('validations/api/admin/place/autocompleteRegion.result.success')
        );
    }

    /**
     * @param AutocompleteCityRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     */
    public function autocompleteCity(
        AutocompleteCityRequest $request
    ) : JsonResponse
    {
        /**
         * Getting autocomplete city predictions
         */
        $predictions = $this->googlePlaceService->autocompleteCity(
            LanguageList::getEnglish(),
            $request->input('search')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($predictions, new PredictionCityTransformer),
            trans('validations/api/admin/place/autocompleteCity.result.success')
        );
    }
}
