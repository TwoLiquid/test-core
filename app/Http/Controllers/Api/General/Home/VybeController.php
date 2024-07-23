<?php

namespace App\Http\Controllers\Api\General\Home;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Home\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\General\Home\Vybe\GetOrderedByAuthUserVybesRequest;
use App\Http\Requests\Api\General\Home\Vybe\GetVybesByFollowingUsersRequest;
use App\Http\Requests\Api\General\Home\Vybe\GetVybesRecommendedForMeRequest;
use App\Http\Requests\Api\General\Home\Vybe\GetVybesNotDiscoveredRequest;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Dashboard\Vybe\VybeTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\General\Home
 */
final class VybeController extends BaseController implements VybeControllerInterface
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
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param GetOrderedByAuthUserVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getOrderedByAuthUserVybes(
        GetOrderedByAuthUserVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getOrderedByUserVybesPaginated(
            AuthService::user(),
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/general/home/vybe/getOrderedByAuthUserVybes.result.success')
        );
    }

    /**
     * @param GetVybesByFollowingUsersRequest $request
     * 
     * @return JsonResponse
     * 
     * @throws DatabaseException
     */
    public function getVybesByFollowingUsers(
        GetVybesByFollowingUsersRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getVybesByUsersPaginated(
            AuthService::user()->subscriptions,
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/general/home/vybe/getVybesByFollowingUsers.result.success')
        );
    }

    /**
     * @param GetVybesNotDiscoveredRequest $request
     * 
     * @return JsonResponse
     * 
     * @throws DatabaseException
     */
    public function getVybesNotDiscovered(
        GetVybesNotDiscoveredRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getVybesNotDiscoveredPaginated(
            AuthService::user(),
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/general/home/vybe/getVybesNotDiscovered.result.success')
        );
    }

    /**
     * @param GetVybesRecommendedForMeRequest $request
     * 
     * @return JsonResponse
     * 
     * @throws DatabaseException
     */
    public function getVybesRecommendedForMe(
        GetVybesRecommendedForMeRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getVybesRecommendedForUserPaginated(
            AuthService::user(),
            $request->input('page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            new Collection($vybes->items())
                        )
                    )
                )
            ), trans('validations/api/general/home/vybe/getVybesRecommendedForMe.result.success')
        );
    }
}