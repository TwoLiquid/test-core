<?php

namespace App\Http\Controllers\Api\Guest\Search;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Search\Interfaces\ActivityControllerInterface;
use App\Http\Requests\Api\Guest\Activity\IndexRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Transformers\Api\Guest\Search\ActivitySearchTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class ActivityController
 *
 * @package App\Http\Controllers\Api\Guest\Search
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
            $this->transformItem([],
                new ActivitySearchTransformer(
                    new Collection($activities->items()),
                    $this->activityImageRepository->getByActivities(
                        new Collection($activities->items())
                    )
                )
            )['activity_search'],
            trans('validations/api/guest/activity/index.result.success')
        );
    }
}
