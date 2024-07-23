<?php

namespace App\Http\Controllers\Api\Guest\Platform;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Platform\Interfaces\PlatformControllerInterface;
use App\Http\Requests\Api\Guest\Platform\IndexRequest;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Transformers\Api\Guest\Platform\PlatformTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class PlatformController
 *
 * @package App\Http\Controllers\Api\Guest\Platform
 */
class PlatformController extends BaseController implements PlatformControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * PlatformController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var  platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();
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
             * Getting platforms with pagination
             */
            $platforms = $this->platformRepository->getAllPaginated(
                $request->input('page')
            );

            return $this->setPagination($platforms)->respondWithSuccess(
                $this->transformCollection($platforms, new PlatformTransformer(
                    $this->platformIconRepository->getByPlatforms(
                        new Collection($platforms->items())
                    )
                )), trans('validations/api/guest/platform/index.result.success')
            );
        }

        /**
         * Getting platforms
         */
        $platforms = $this->platformRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($platforms, new PlatformTransformer(
                $this->platformIconRepository->getByPlatforms(
                    $platforms
                )
            )), trans('validations/api/guest/platform/index.result.success')
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
         * Getting platform
         */
        $platform = $this->platformRepository->findById($id);

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/guest/platform/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($platform, new PlatformTransformer(
                $this->platformIconRepository->getByPlatforms(
                    new Collection([$platform])
                )
            )), trans('validations/api/guest/platform/show.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByActivity(
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
                trans('validations/api/guest/platform/getByActivity.result.error.find')
            );
        }

        /**
         * Getting platforms
         */
        $platforms = $this->platformRepository->getByActivity(
            $activity
        );

        return $this->respondWithSuccess(
            $this->transformCollection($platforms, new PlatformTransformer(
                $this->platformIconRepository->getByPlatforms(
                    $platforms
                )
            )), trans('validations/api/guest/platform/getByActivity.result.success')
        );
    }
}
