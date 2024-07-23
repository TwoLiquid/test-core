<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Csau\Category\Interfaces\SubcategoryControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Category\Subcategory\StoreRequest;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Category\CategoryService;
use App\Services\File\MediaService;
use App\Transformers\Api\Admin\Csau\Category\CategoryTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category
 */
final class SubcategoryController extends BaseController implements SubcategoryControllerInterface
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
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * SubcategoryController constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var CategoryService categoryService */
        $this->categoryService = new CategoryService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            /**
             * Validating subcategory icon
             */
            $this->mediaService->validateCategoryIcon(
                $request->input('icon.content'),
                $request->input('icon.mime')
            );
        }

        /**
         * Getting parent category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Creating subcategory
         */
        $subcategory = $this->categoryRepository->store(
            $category,
            $request->input('name'),
            $request->input('visible')
        );

        /**
         * Checking subcategory existence
         */
        if (!$subcategory) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/subcategory/store.result.error.create')
            );
        }

        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Uploading category icon
                 */
                $this->mediaMicroservice->storeCategoryIcon(
                    $subcategory,
                    $request->input('icon.content'),
                    $request->input('icon.extension'),
                    $request->input('icon.mime')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        new Collection([$category])
                    )
                )
            ), trans('validations/api/admin/csau/category/store.result.success')
        );
    }
}
