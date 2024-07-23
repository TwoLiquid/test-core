<?php

namespace App\Http\Controllers\Api\Admin\General\TaxRule;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\General\TaxRule\Interfaces\TaxRuleRegionControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\TaxRule\Region\IndexRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Region\StoreRequest;
use App\Http\Requests\Api\Admin\General\TaxRule\Region\UpdateRequest;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Repositories\TaxRule\TaxRuleCountryRepository;
use App\Repositories\TaxRule\TaxRuleRegionHistoryRepository;
use App\Repositories\TaxRule\TaxRuleRegionRepository;
use App\Services\Place\RegionPlaceService;
use App\Transformers\Api\Admin\General\TaxRule\Region\TaxRuleRegionListTransformer;
use App\Transformers\Api\Admin\General\TaxRule\Region\TaxRuleRegionTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use Illuminate\Http\JsonResponse;

/**
 * Class TaxRuleRegionController
 *
 * @package App\Http\Controllers\Api\Admin\General\TaxRule
 */
final class TaxRuleRegionController extends BaseController implements TaxRuleRegionControllerInterface
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
     * @var RegionPlaceService
     */
    protected RegionPlaceService $regionPlaceService;

    /**
     * @var TaxRuleCountryRepository
     */
    protected TaxRuleCountryRepository $taxRuleCountryRepository;

    /**
     * @var TaxRuleRegionRepository
     */
    protected TaxRuleRegionRepository $taxRuleRegionRepository;

    /**
     * @var TaxRuleRegionHistoryRepository
     */
    protected TaxRuleRegionHistoryRepository $taxRuleRegionHistoryRepository;

    /**
     * TaxRuleRegionController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();

        /** @var RegionPlaceService regionPlaceService */
        $this->regionPlaceService = new RegionPlaceService();

        /** @var TaxRuleCountryRepository taxRuleCountryRepository */
        $this->taxRuleCountryRepository = new TaxRuleCountryRepository();

        /** @var TaxRuleRegionRepository taxRuleRegionRepository */
        $this->taxRuleRegionRepository = new TaxRuleRegionRepository();

        /** @var TaxRuleRegionHistoryRepository taxRuleRegionHistoryRepository */
        $this->taxRuleRegionHistoryRepository = new TaxRuleRegionHistoryRepository();
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
                 * Getting a tax rule regions with pagination and search
                 */
                $taxRuleRegions = $this->taxRuleRegionRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting a tax rule regions with pagination
                 */
                $taxRuleRegions = $this->taxRuleRegionRepository->getAllPaginated(
                    $request->input('page'),
                    $request->input('per_page')
                );
            }

            return $this->setPagination($taxRuleRegions)->respondWithSuccess(
                $this->transformCollection($taxRuleRegions, new TaxRuleRegionListTransformer),
                trans('validations/api/admin/general/taxRule/region/index.result.success')
            );
        }

        /**
         * Checking search string existence
         */
        if ($request->input('search')) {

            /**
             * Getting tax rules regions by search
             */
            $taxRuleRegions = $this->taxRuleRegionRepository->getAllBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting tax rules regions
             */
            $taxRuleRegions = $this->taxRuleRegionRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection($taxRuleRegions, new TaxRuleRegionListTransformer),
            trans('validations/api/admin/general/taxRule/region/index.result.success')
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
         * Getting a tax rule region
         */
        $taxRuleRegion = $this->taxRuleRegionRepository->findById($id);

        /**
         * Checking a tax rule region existence
         */
        if (!$taxRuleRegion) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleRegion, new TaxRuleRegionTransformer),
            trans('validations/api/admin/general/taxRule/region/show.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws TranslateException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $this->taxRuleCountryRepository->findById(
            $request->input('tax_rule_country_id')
        );

        /**
         * Checking tax rule country existence
         */
        if (!$taxRuleCountry) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/show.result.error.find')
            );
        }

        /**
         * Getting region place
         */
        $regionPlace = $this->regionPlaceRepository->findByPlaceId(
            $request->input('region_place_id')
        );

        /**
         * Checking region place existence
         */
        if (!$regionPlace) {

            /**
             * Creating region place
             */
            $regionPlace = $this->regionPlaceService->getOrCreate(
                $taxRuleCountry->countryPlace,
                $request->input('region_place_id'),
                $request->input('region_code')
            );
        }

        /**
         * Checking a tax rule region existence
         */
        if ($this->taxRuleRegionRepository->regionPlaceExists(
            $regionPlace
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/store.result.error.exists')
            );
        }

        /**
         * Creating a tax rule region
         */
        $taxRuleRegion = $this->taxRuleRegionRepository->store(
            $taxRuleCountry,
            $regionPlace,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        /**
         * Checking a tax rule region existence
         */
        if (!$taxRuleRegion) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/store.result.error.create')
            );
        }

        /**
         * Creating a tax rule region history
         */
        $this->taxRuleRegionHistoryRepository->store(
            $taxRuleRegion,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleRegion, new TaxRuleRegionTransformer),
            trans('validations/api/admin/general/taxRule/region/store.result.success')
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
         * Getting a tax rule region
         */
        $taxRuleRegion = $this->taxRuleRegionRepository->findById($id);

        /**
         * Checking a tax rule region existence
         */
        if (!$taxRuleRegion) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/update.result.error.find')
            );
        }

        /**
         * Updating a tax rule region
         */
        $taxRuleRegion = $this->taxRuleRegionRepository->update(
            $taxRuleRegion,
            $request->input('tax_rate'),
            $request->input('from_date')
        );

        /**
         * Getting last a tax rule region history
         */
        $taxRuleRegionHistory = $this->taxRuleRegionHistoryRepository->findLastForTaxRuleRegion(
            $taxRuleRegion
        );

        /**
         * Checking last a tax rule region history
         */
        if ($taxRuleRegionHistory) {

            /**
             * Updating a tax rule region history
             */
            $this->taxRuleRegionHistoryRepository->update(
                $taxRuleRegionHistory,
                $request->input('tax_rate'),
                $request->input('from_date')
            );

            /**
             * Creating a tax rule region history
             */
            $this->taxRuleRegionHistoryRepository->store(
                $taxRuleRegion,
                $request->input('tax_rate'),
                $request->input('from_date')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($taxRuleRegion, new TaxRuleRegionTransformer),
            trans('validations/api/admin/general/taxRule/region/update.result.success')
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
         * Getting a tax rule region
         */
        $taxRuleRegion = $this->taxRuleRegionRepository->findById($id);

        /**
         * Checking a tax rule region existence
         */
        if (!$taxRuleRegion) {
            return $this->respondWithError(
                trans('validations/api/admin/general/taxRule/region/destroy.result.error.find')
            );
        }

        /**
         * Deleting a tax rule region
         */
        $this->taxRuleRegionRepository->delete(
            $taxRuleRegion
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/taxRule/region/destroy.result.success')
        );
    }
}
