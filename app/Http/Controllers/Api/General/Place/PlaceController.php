<?php

namespace App\Http\Controllers\Api\General\Place;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Place\Interfaces\PlaceControllerInterface;
use App\Http\Requests\Api\General\Place\AutocompleteCityRequest;
use App\Services\Auth\AuthService;
use App\Services\Google\GooglePlaceService;
use App\Transformers\Api\General\Place\PredictionCityTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PlaceController
 *
 * @package App\Http\Controllers\Api\General\Place
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
     * @param AutocompleteCityRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function autocompleteCity(
        AutocompleteCityRequest $request
    ) : JsonResponse
    {
        /**
         * Getting autocomplete city predictions
         */
        $predictions = $this->googlePlaceService->autocompleteCity(
            AuthService::user()->getLanguage(),
            $request->input('search')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($predictions, new PredictionCityTransformer),
            trans('validations/api/general/place/autocompleteCity.result.success')
        );
    }
}
