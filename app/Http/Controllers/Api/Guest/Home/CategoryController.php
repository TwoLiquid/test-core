<?php

namespace App\Http\Controllers\Api\Guest\Home;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Home\Interfaces\CategoryControllerInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Home\Category\CategoryTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Guest\Home
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
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * CategoryController constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting categories
         */
        $categories = $this->categoryRepository->getCategoriesForHomeWithVybes();

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformCollection(
                $categories,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $categories
                    ),
                    $this->vybeImageRepository->getByVybes(
                        $this->vybeService->getByCategories(
                            $categories
                        )
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $this->vybeService->getByCategories(
                            $categories
                        )
                    )
                )
            ), trans('validations/api/guest/home/category/index.result.success')
        );
    }
}
