<?php

namespace App\Http\Controllers\Api\Admin\General\TaxRule;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\General\TaxRule\Interfaces\TaxRuleCountryControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\TaxRule\Country\IndexRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Country\ShowRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Country\StoreRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Country\UpdateRequest;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\TaxRule\TaxRuleCountryHistoryRepository;
use App\Repositories\TaxRule\TaxRuleCountryRepository;
use App\Transformers\Api\Admin\General\TaxRule\Country\TaxRuleCountryListTransformer;
use App\Transformers\Api\Admin\General\TaxRule\Country\TaxRuleCountryTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class TaxRuleCountryController
 *
 * @package App\Http\Controllers\Api\Admin\General\TaxRule
 */
final class TaxRuleCountryController extends BaseController implements TaxRuleCountryControllerInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var TaxRuleCountryRepository
     */
    protected TaxRuleCountryRepository $taxRuleCountryRepository;

    /**
     * @var TaxRuleCountryHistoryRepository
     */
    protected TaxRuleCountryHistoryRepository $taxRuleCountryHistoryRepository;

    /**
     * TaxRuleCountryController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var TaxRuleCountryRepository taxRuleCountryRepository */
        $this->taxRuleCountryRepository = new TaxRuleCountryRepository();

        /** @var TaxRuleCountryHistoryRepository taxRuleCountryHistoryRepository */
        $this->taxRuleCountryHistoryRepository = new TaxRuleCountryHistoryRepository();
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
         * Checking pagination exists
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search string existence
             */
            if ($request->input('search')) {

                /**
                 * Getting tax rule countries with pagination and search
                 */
                $taxRuleCountries = $this->taxRuleCountryRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting tax rule countries with pagination
                 */
                $taxRuleCountries = $this->taxRuleCountryRepository->getAllPaginated(
                    $request->input('page')
                );
            }

            return $this->setPagination($taxRuleCountries)->respondWithSuccess(
                $this->transformCollection($taxRuleCountries, new TaxRuleCountryListTransformer),
                trans('validations/api/admin/general/taxRule/country/index.result.success')
            );
        }

        /**
         * Checking search string existence
         */
        if ($request->input('search')) {

            /**
             * Getting tax rules countries by search
             */
            $taxRuleCountries = $this->taxRuleCountryRepository->getAllBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting tax rules countries
             */
            $taxRuleCountries = $this->taxRuleCountryRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection($taxRuleCountries, new TaxRuleCountryListTransformer),
            trans('validations/api/admin/general/taxRule/country/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse
    {
        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->findByIdWithSearch(
            $id,
            $request->input('search')
        );

        /**
         * Checking tax rule country existence
         */
        if (!$taxRuleCountry) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/country/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleCountry, new TaxRuleCountryTransformer),
            trans('validations/api/admin/general/taxRule/country/show.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $request->input('country_place_id')
        );

        /**
         * Checking tax rule country existence
         */
        if ($this->taxRuleCountryRepository->countryPlaceExists(
            $countryPlace
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/country/store.result.error.exists')
            );
        }

        /**
         * Creating tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->store(
            $countryPlace,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        /**
         * Checking tax rule country existence
         */
        if (!$taxRuleCountry) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/country/store.result.error.create')
            );
        }

        /**
         * Creating tax rule country history
         */
        $this->taxRuleCountryHistoryRepository->store(
            $taxRuleCountry,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleCountry, new TaxRuleCountryTransformer),
            trans('validations/api/admin/general/taxRule/country/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->findById($id);

        /**
         * Checking tax rule country existence
         */
        if (!$taxRuleCountry) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/country/update.result.error.find')
            );
        }

        /**
         * Updating tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->update(
            $taxRuleCountry,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        /**
         * Getting last tax rule country history
         */
        $taxRuleCountryHistory = $this->taxRuleCountryHistoryRepository->findLastForTaxRuleCountry(
            $taxRuleCountry
        );

        /**
         * Checking the last tax rule country history
         */
        if ($taxRuleCountryHistory) {

            /**
             * Updating tax rule country history
             */
            $this->taxRuleCountryHistoryRepository->update(
                $taxRuleCountryHistory,
                $request->input('tax_rate'),
                $request->input('from_date')
            );

            /**
             * Creating tax rule country history
             */
            $this->taxRuleCountryHistoryRepository->store(
                $taxRuleCountry,
                $request->input('tax_rate'),
                $request->input('from_date')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleCountry, new TaxRuleCountryTransformer),
            trans('validations/api/admin/general/taxRule/country/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->findById($id);

        /**
         * Checking tax rule country existence
         */
        if (!$taxRuleCountry) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/country/destroy.result.error.find')
            );
        }

        /**
         * Deleting tax rule country
         */
        $this->taxRuleCountryRepository->delete(
            $taxRuleCountry
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/taxRule/country/destroy.result.success')
        );
    }
}
