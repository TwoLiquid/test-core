<?php

namespace App\Http\Controllers\Api\Guest\Catalog;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Catalog\Interfaces\SubcategoryControllerInterface;
use App\Http\Requests\Api\Guest\Catalog\Subcategory\GetByCodeRequest;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Activity\ActivityService;
use App\Services\Category\CategoryService;
use App\Transformers\Api\Guest\Catalog\Subcategory\SubcategoryTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Catalog
 */
class SubcategoryController extends BaseController implements SubcategoryControllerInterface
{
    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;
    
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * SubcategoryController constructor
     */
    public function __construct()
    {
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var CategoryService categoryService */
        $this->categoryService = new CategoryService();
    }
    
    /**
     * @param GetByCodeRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByCode(
        GetByCodeRequest $request
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findFullSubcategoryByCode(
            $request->input('code')
        );

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/guest/catalog/subcategory/getByCode.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $category,
                new SubcategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->categoryService->getCategoryWithSubcategories(
                            $category
                        )
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByCategory(
                            $category
                        )
                    ),
                    $this->categoryRepository->getPerPage(),
                    $request->input('page')
                )
            ), trans('validations/api/guest/catalog/subcategory/getByCode.result.success')
        );
    }
}
