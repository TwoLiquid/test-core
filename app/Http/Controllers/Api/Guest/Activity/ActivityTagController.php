<?php

namespace App\Http\Controllers\Api\Guest\Activity;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Activity\Interfaces\ActivityTagControllerInterface;
use App\Http\Requests\Api\Guest\Activity\Tag\IndexRequest;
use App\Repositories\Activity\ActivityTagRepository;
use App\Repositories\Category\CategoryRepository;
use App\Transformers\Api\Guest\Activity\Tag\ActivityTagTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class ActivityTagController
 *
 * @package App\Http\Controllers\Api\Guest\Activity
 */
class ActivityTagController extends BaseController implements ActivityTagControllerInterface
{
    /**
     * @var ActivityTagRepository
     */
    protected ActivityTagRepository $activityTagRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * ActivityTagController constructor.
     */
    public function __construct()
    {
        /** @var ActivityTagRepository activityTagRepository */
        $this->activityTagRepository = new ActivityTagRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();
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
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Getting subcategory
         */
        $subcategory = $this->categoryRepository->findById(
            $request->input('subcategory_id')
        );

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search existence
             */
            if ($request->input('search')) {

                /**
                 * Getting activity tags by search with paginated
                 */
                $activityTags = $this->activityTagRepository->getAllBySearchAndCategoriesPaginated(
                    $request->input('search'),
                    $category,
                    $subcategory,
                    $request->input('page')
                );
            } else {

                /**
                 * Getting activity tags with paginated
                 */
                $activityTags = $this->activityTagRepository->getAllByCategoriesPaginated(
                    $category,
                    $subcategory,
                    $request->input('page')
                );
            }

            return $this->setPagination($activityTags)->respondWithSuccess(
                $this->transformCollection(
                    $activityTags,
                    new ActivityTagTransformer
                ), trans('validations/api/guest/activity/tag/index.result.success')
            );
        }

        if ($request->input('search')) {

            /**
             * Getting activity tags by search with pagination
             */
            $activityTags = $this->activityTagRepository->getAllBySearchAndCategories(
                $request->input('search'),
                $category,
                $subcategory
            );
        } else {

            /**
             * Getting activity tags
             */
            $activityTags = $this->activityTagRepository->getAllByCategories(
                $category,
                $subcategory
            );
        }

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activityTags,
                new ActivityTagTransformer
            ), trans('validations/api/guest/activity/tag/index.result.success')
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
         * Getting activity
         */
        $activityTag = $this->activityTagRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/guest/activity/tag/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer
            ), trans('validations/api/guest/activity/tag/show.result.success')
        );
    }
}
