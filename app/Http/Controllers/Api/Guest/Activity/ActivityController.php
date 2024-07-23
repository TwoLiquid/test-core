<?php

namespace App\Http\Controllers\Api\Guest\Activity;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Activity\Interfaces\ActivityControllerInterface;
use App\Http\Requests\Api\Guest\Activity\GetByCategoriesRequest;
use App\Http\Requests\Api\Guest\Activity\GetByCategoryRequest;
use App\Http\Requests\Api\Guest\Activity\IndexRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Services\Activity\ActivityService;
use App\Transformers\Api\Guest\Activity\ActivityTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class ActivityController
 *
 * @package App\Http\Controllers\Api\Guest\Activity
 */
class ActivityController extends BaseController implements ActivityControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * ActivityController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();
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
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search existence
             */
            if ($request->input('search')) {

                /**
                 * Getting activities by search with paginated
                 */
                $activities = $this->activityRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page')
                );
            } else {

                /**
                 * Getting activities with paginated
                 */
                $activities = $this->activityRepository->getAllPaginated(
                    $request->input('page')
                );
            }

            return $this->setPagination($activities)->respondWithSuccess(
                $this->transformCollection(
                    $activities,
                    new ActivityTransformer(
                        $this->activityImageRepository->getByActivities(
                            new Collection($activities->items())
                        )
                    )
                ), trans('validations/api/guest/activity/index.result.success')
            );
        }

        if ($request->input('search')) {

            /**
             * Getting activities by search with pagination
             */
            $activities = $this->activityRepository->getAllBySearchPaginated(
                $request->input('search')
            );
        } else {

            /**
             * Getting activities
             */
            $activities = $this->activityRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/guest/activity/index.result.success')
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
        $activity = $this->activityRepository->findById($id);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/guest/activity/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $activity,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection([$activity])
                    )
                )
            ), trans('validations/api/guest/activity/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetByCategoryRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByCategory(
        int $id,
        GetByCategoryRequest $request
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
                trans('validations/api/guest/activity/getByCategory.result.error.find')
            );
        }

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search existence
             */
            if ($request->input('search')) {

                /**
                 * Getting activities by search with paginated
                 */
                $activities = $this->activityRepository->getAllByCategoryWithSearchPaginated(
                    $category,
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting activities with paginated
                 */
                $activities = $this->activityRepository->getAllByCategoryPaginated(
                    $category,
                    $request->input('page'),
                    $request->input('per_page')
                );
            }

            return $this->setPagination($activities)->respondWithSuccess(
                $this->transformCollection(
                    $activities,
                    new ActivityTransformer(
                        $this->activityImageRepository->getByActivities(
                            new Collection($activities->items())
                        )
                    )
                ), trans('validations/api/guest/activity/getByCategory.result.success')
            );
        }

        if ($request->input('search')) {

            /**
             * Getting activities by search with pagination
             */
            $activities = $this->activityRepository->getAllByCategoryWithSearch(
                $category,
                $request->input('search')
            );
        } else {

            /**
             * Getting activities
             */
            $activities = $this->activityRepository->getAllByCategory(
                $category
            );
        }

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/guest/activity/getByCategory.result.success')
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
         * Getting activities
         */
        $activities = $this->activityRepository->getByCategoriesIds(
            $request->input('categories_ids'),
            $request->input('name')
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/guest/activity/getByCategories.result.success')
        );
    }
}
