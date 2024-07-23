<?php

namespace App\Http\Controllers\Api\Guest\Place;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Place\Interfaces\RegionPlaceControllerInterface;
use App\Http\Requests\Api\Guest\Place\Region\IndexRequest;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Transformers\Api\Guest\Place\RegionPlaceTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class RegionPlaceController
 *
 * @package App\Http\Controllers\Api\Guest\Place
 */
class RegionPlaceController extends BaseController implements RegionPlaceControllerInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * RegionPlaceController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();
    }

    /**
     * @param string $countryPlaceId
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        string $countryPlaceId,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $countryPlaceId
        );

        /**
         * Checking country place existence
         */
        if (!$countryPlace) {
            return $this->respondWithError(
                trans('validations/api/guest/place/region/show.result.error.find')
            );
        }

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting region places with paginated
             */
            $regionPlaces = $this->regionPlaceRepository->getAllByCountryPlacePaginated(
                $countryPlace,
                $request->input('page'),
                $request->input('per_page')
            );

            /**
             * Returning without media
             */
            return $this->setPagination($regionPlaces)->respondWithSuccess(
                $this->transformCollection($regionPlaces, new RegionPlaceTransformer),
                trans('validations/api/guest/place/region/index.result.success')
            );
        }

        /**
         * Getting region places
         */
        $regionPlaces = $this->regionPlaceRepository->getAllByCountryPlace(
            $countryPlace
        );

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformCollection($regionPlaces, new RegionPlaceTransformer),
            trans('validations/api/guest/place/region/index.result.success')
        );
    }
}
