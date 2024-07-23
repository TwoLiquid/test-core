<?php

namespace App\Http\Controllers\Api\Guest\Place;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Place\Interfaces\CountryPlaceControllerInterface;
use App\Http\Requests\Api\Guest\Place\Country\IndexRequest;
use App\Repositories\Place\CountryPlaceRepository;
use App\Transformers\Api\Guest\Place\CountryPlaceTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CountryPlaceController
 *
 * @package App\Http\Controllers\Api\Guest\Place
 */
class CountryPlaceController extends BaseController implements CountryPlaceControllerInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * CountryPlaceController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting country places with paginated
             */
            $countryPlaces = $this->countryPlaceRepository->getAllPaginated(
                $request->input('page')
            );

            /**
             * Returning without media
             */
            return $this->setPagination($countryPlaces)->respondWithSuccess(
                $this->transformCollection($countryPlaces, new CountryPlaceTransformer),
                trans('validations/api/guest/place/country/index.result.success')
            );
        }

        /**
         * Getting country places
         */
        $countryPlaces = $this->countryPlaceRepository->getAll();

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformCollection($countryPlaces, new CountryPlaceTransformer),
            trans('validations/api/guest/place/country/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findById($id);

        /**
         * Checking country place existence
         */
        if (!$countryPlace) {
            return $this->respondWithError(
                trans('validations/api/guest/place/country/show.result.error.find')
            );
        }

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem($countryPlace, new CountryPlaceTransformer),
            trans('validations/api/guest/place/country/show.result.success')
        );
    }
}
