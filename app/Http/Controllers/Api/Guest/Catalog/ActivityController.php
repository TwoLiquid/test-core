<?php

namespace App\Http\Controllers\Api\Guest\Catalog;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Catalog\Interfaces\ActivityControllerInterface;
use App\Http\Requests\Api\Guest\Catalog\Activity\GetByCategoryRequest;
use App\Http\Requests\Api\Guest\Catalog\Activity\GetByCodeRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Catalog\Activity\ActivityTransformer;
use App\Transformers\Api\Guest\Catalog\Activity\ActivityShortTransformer;
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

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
         * Getting activity
         */
        $activity = $this->activityRepository->findByCode(
            $request->input('code')
        );

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/guest/catalog/activity/getByCode.result.error.find')
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
            ), trans('validations/api/guest/catalog/activity/getByCode.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getRelatedActivity(
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
                trans('validations/api/guest/catalog/activity/relatedActivity.result.error.find.activity')
            );
        }

        /**
         * Finding related activity
         */
        $relatedActivity = $this->activityRepository->findRelatedActivity(
            $activity
        );

        /**
         * Checking related activity existence
         */
        if (!$relatedActivity) {
            return $this->respondWithError(
                trans('validations/api/guest/catalog/activity/relatedActivity.result.error.find.related')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $relatedActivity,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection([$relatedActivity])
                    ),
                    $this->vybeImageRepository->getByVybes(
                        $this->vybeService->getByActivity(
                            $relatedActivity
                        )
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $this->vybeService->getByActivity(
                            $relatedActivity
                        )
                    )
                )
            ), trans('validations/api/guest/catalog/activity/relatedActivity.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getPopularActivities() : JsonResponse
    {
        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getPopularActivities();

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityShortTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/guest/catalog/activity/getPopulars.result.success')
        );
    }

    /**
     * @param int $id
     * 
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
                trans('validations/api/guest/catalog/activity/getByCategory.result.error.find')
            );
        }

        /**
         * Checking is pagination enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting activities
             */
            $activities = $this->activityRepository->getByCategoryPaginated(
                $category,
                $request->input('page')
            );

            return $this->setPagination($activities)->respondWithSuccess(
                $this->transformCollection(
                    $activities,
                    new ActivityShortTransformer(
                        $this->activityImageRepository->getByActivities(
                            new Collection($activities->items())
                        )
                    )
                ), trans('validations/api/guest/activity/getByCategory.result.success')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByCategory(
            $category
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityShortTransformer(
                    $this->activityImageRepository->getByActivities(
                        $activities
                    )
                )
            ), trans('validations/api/guest/activity/getByCategory.result.success')
        );
    }
}
