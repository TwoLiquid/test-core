<?php

namespace App\Http\Controllers\Api\Guest\Search;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Search\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\Guest\Search\Vybe\IndexRequest;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Transformers\Api\Guest\Search\VybeSearchTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Guest\Search
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
             * Getting all vybes by search with pagination
             */
            $vybes = $this->vybeRepository->getAllBySearchPaginated(
                $request->input('search'),
                $request->input('page')
            );
        } else {

            /**
             * Getting vybes with pagination
             */
            $vybes = $this->vybeRepository->getAllPaginated(
                $request->input('page')
            );
        }

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformItem([],
                new VybeSearchTransformer(
                    AuthService::user(),
                    new Collection($vybes->items()),
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
            )['vybe_search'],
            trans('validations/api/main/general/vybe/index.result.success')
        );
    }
}
