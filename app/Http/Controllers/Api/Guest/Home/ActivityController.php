<?php

namespace App\Http\Controllers\Api\Guest\Home;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Home\Interfaces\ActivityControllerInterface;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Transformers\Api\Guest\Home\Activity\ActivityShortTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class ActivityController
 *
 * @package App\Http\Controllers\Api\Guest\Home
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
     * ActivityController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();
    }

    /**
     * @return JsonResponse
     * 
     * @throws DatabaseException
     */
    public function index() : JsonResponse
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
            ), trans('validations/api/guest/home/activity/index.result.success')
        );
    }
}
