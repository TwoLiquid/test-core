<?php

namespace App\Http\Controllers\Api\Guest\Catalog;

use App\Http\Controllers\Api\BaseController;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Guest\Catalog\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\Guest\Catalog\Vybe\GetFormRequest;
use App\Http\Requests\Api\Guest\Catalog\Vybe\SearchWithFiltersRequest;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Transformers\Api\Guest\Catalog\Vybe\FormTransformer;
use App\Transformers\Api\Guest\Catalog\Vybe\VybeTransformer;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Vybe\VybeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Guest\Catalog
 */
class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param GetFormRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getForm(
        GetFormRequest $request
    ) : JsonResponse
    {
        /**
         * Checking category id existence
         */
        if ($request->input('category_id')) {
            
            /**
             * Getting category
             */
            $category = $this->categoryRepository->findById(
                $request->input('category_id')
            );

            /**
             * Checking category existence
             */
            if (!$category) {
                return $this->respondWithError(
                    trans('validations/api/guest/catalog/vybe/getForm.result.error.find.category')
                );
            }

            return $this->respondWithSuccess(
                $this->transformItem([], new FormTransformer($category)),
                trans('validations/api/guest/catalog/vybe/getForm.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([], new FormTransformer),
            trans('validations/api/guest/catalog/vybe/getForm.result.success')
        );
    }

    /**
     * @param SearchWithFiltersRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function searchWithFilters(
        SearchWithFiltersRequest $request
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Getting vybes and filters values by filters
         */
        $vybes = $this->vybeRepository->getWithFiltersForCatalog(
            null,
            $request->input('units_ids'),
            $request->input('appearance_id'),
            $request->input('gender_id'),
            $request->input('city_place_id'),
            $category,
            $request->input('subcategory_id'),
            $request->input('personality_traits_ids'),
            $request->input('activity_id'),
            $request->input('types_ids'),
            $request->input('devices_ids'),
            $request->input('platforms_ids'),
            $request->input('languages_ids'),
            $request->input('tags_ids'),
            $request->input('date_min'),
            $request->input('date_max'),
            $request->input('price_min'),
            $request->input('price_max'),
            $request->input('sort'),
            $request->input('has_all_tags')
        );

        /**
         * Getting filters data
         */
        $filtersData = $this->vybeService->getFiltersForCatalog($vybes);

        /**
         * Getting paginated vybes
         */
        $paginatedVybes = paginateCollection(
            $vybes,
            config('repositories.vybe.perPage')
        );

        return $this->setPagination($paginatedVybes)->respondWithSuccess(
            $this->transformCollection(
                new Collection($paginatedVybes->items()),
                new VybeTransformer(
                    AuthService::user(),
                    $this->vybeImageRepository->getByVybes(
                        new Collection($paginatedVybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($paginatedVybes->items())
                    )
                )
            ) + $filtersData,
            trans('validations/api/guest/catalog/vybe/searchWithFilters.result.success')
        );
    }
}
