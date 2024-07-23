<?php

namespace App\Http\Controllers\Api\Guest\Category;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Category\Interfaces\CategoryControllerInterface;
use App\Http\Requests\Api\Guest\Category\GetByCategoriesRequest;
use App\Http\Requests\Api\Guest\Category\IndexRequest;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Transformers\Api\Guest\Category\CategoryTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Category
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
     * CategoryController constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();
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
         * Checking paginated enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting categories with paginated
             */
            $categories = $this->categoryRepository->getAllPaginated(
                $request->input('page')
            );

            return $this->setPagination($categories)->respondWithSuccess(
                $this->transformCollection(
                    $categories,
                    new CategoryTransformer(
                        $this->categoryIconRepository->getByCategories(
                            new Collection($categories->items())
                        )
                    )
                ), trans('validations/api/guest/category/index.result.success')
            );
        }

        /**
         * Getting categories
         */
        $categories = $this->categoryRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection(
                $categories,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $categories
                    )
                )
            ), trans('validations/api/guest/category/index.result.success')
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
         * Getting category
         */
        $category = $this->categoryRepository->findById($id);

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/guest/category/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        new Collection([$category])
                    )
                )
            ), trans('validations/api/guest/category/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByCategory(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/guest/category/getByCategory.result.error.find')
            );
        }

        /**
         * Getting subcategories by category
         */
        $subcategories = $this->categoryRepository->getByCategory(
            $category
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $subcategories,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $subcategories
                    )
                )
            ), trans('validations/api/guest/category/getByCategory.result.success')
        );
    }

    /**
     * @param GetByCategoriesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByCategories(
        GetByCategoriesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting subcategories
         */
        $subcategories = $this->categoryRepository->getByCategoriesIds(
            $request->input('categories_ids')
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $subcategories,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $subcategories
                    )
                )
            ), trans('validations/api/guest/category/getByCategories.result.success')
        );
    }
}
