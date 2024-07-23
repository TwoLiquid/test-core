<?php

namespace App\Http\Controllers\Api\Guest\Catalog;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Catalog\Interfaces\CategoryControllerInterface;
use App\Http\Requests\Api\Guest\Catalog\Category\GetByCodeRequest;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Category\CategoryService;
use App\Transformers\Api\Guest\Catalog\Category\CategoryTransformer;
use App\Transformers\Api\Guest\Catalog\Category\CategoryShortTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Catalog
 */
class CategoryController extends BaseController implements CategoryControllerInterface
{
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
     * CategoryController constructor
     */
    public function __construct()
    {
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
        $category = $this->categoryRepository->findFullByCode(
            $request->input('code')
        );

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/guest/catalog/category/getByCode.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->categoryService->getCategoryWithSubcategories(
                            $category
                        )
                    )
                )
            ), trans('validations/api/guest/catalog/category/getByCode.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getCategoriesForNavbar() : JsonResponse
    {
        /** 
         * Getting categories
         */
        $categories = $this->categoryRepository->getCategoriesForNavbar();

        return $this->respondWithSuccess(
            $this->transformCollection(
                $categories,
                new CategoryShortTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $categories
                    )
                )
            ), trans('validations/api/guest/catalog/category/getCategoriesForNavbar.result.success')
        );
    }
}
