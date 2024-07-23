<?php

namespace App\Http\Controllers\Api\Admin\Csau\Platform;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Platform\Interfaces\PlatformControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Device\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\AttachActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\DetachActivityRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\GetActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Platform\UpdateRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\File\MediaService;
use App\Services\Platform\PlatformService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Platform\Activity\ActivityTransformer;
use App\Transformers\Api\Admin\Csau\Platform\PlatformListTransformer;
use App\Transformers\Api\Admin\Csau\Platform\PlatformTransformer;
use App\Transformers\Api\Admin\Csau\Platform\PlatformWithPaginationTransformer;
use App\Transformers\Api\Admin\Csau\Platform\VybeTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PlatformController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Platform
 */
final class PlatformController extends BaseController implements PlatformControllerInterface
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
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var PlatformService
     */
    protected PlatformService $platformService;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * PlatformController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var PlatformService platformService */
        $this->platformService = new PlatformService();

        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
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
         * Checking pagination enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting platforms
             */
            $platforms = $this->platformRepository->getAllPaginated(
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($platforms)->respondWithSuccess(
                $this->transformCollection($platforms, new PlatformListTransformer(
                    $this->platformIconRepository->getByPlatforms(
                        new Collection($platforms->items())
                    )
                )), trans('validations/api/admin/csau/platform/index.result.success')
            );
        }

        /**
         * Getting platforms
         */
        $platforms = $this->platformRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($platforms, new PlatformListTransformer(
                $this->platformIconRepository->getByPlatforms(
                    $platforms
                )
            )), trans('validations/api/admin/csau/platform/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse
    {
        /**
         * Getting platform
         */
        $platform = $this->platformRepository->findFullForAdminById($id);

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/show.result.error.find')
            );
        }

        /**
         * Checking pagination is enabled
         */
        if ($request->input('paginated') === true) {
            return $this->respondWithSuccess(
                $this->transformItem(
                    $platform,
                    new PlatformWithPaginationTransformer(
                        $this->platformIconRepository->getByPlatforms(
                            new Collection([$platform])
                        ),
                        $request->input('page'),
                        $request->input('per_page')
                    )
                ), trans('validations/api/admin/csau/platform/show.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $platform,
                new PlatformTransformer(
                    $this->platformIconRepository->getByPlatforms(
                        new Collection([$platform])
                    )
                )
            ), trans('validations/api/admin/csau/platform/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getVybes(
        int $id,
        GetVybesRequest $request
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
                trans('validations/api/admin/csau/platform/getVybes.result.error.find')
            );
        }

        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getByPlatformPaginated(
            $platform,
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer
            ), trans('validations/api/admin/csau/platform/getVybes.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getActivities(
        int $id,
        GetActivitiesRequest $request
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
                trans('validations/api/admin/csau/platform/getActivities.result.error.find')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByPlatformPaginated(
            $platform,
            $request->input('search'),
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($activities)->respondWithSuccess(
            $this->transformCollection(
                $activities,
                new ActivityTransformer(
                    $this->activityImageRepository->getByActivities(
                        new Collection($activities->items())
                    )
                )
            ), trans('validations/api/admin/csau/platform/getActivities.result.success')
        );
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
             * Validating platform icon
             */
            $this->mediaService->validatePlatformIcon(
                $request->input('icon.content'),
                $request->input('icon.mime')
            );
        }

        /**
         * Creating platform
         */
        $platform = $this->platformRepository->store(
            $request->input('name'),
            $request->input('voice_chat'),
            $request->input('visible_in_voice_chat'),
            $request->input('video_chat'),
            $request->input('visible_in_video_chat')
        );

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/store.result.error.create')
            );
        }

        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Uploading platform icon
                 */
                $this->mediaMicroservice->storePlatformIcon(
                    $platform,
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
            $this->transformItem($platform, new PlatformListTransformer(
                $this->platformIconRepository->getByPlatforms(
                    new Collection([$platform])
                )
            )), trans('validations/api/admin/csau/platform/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function update(
        int $id,
        UpdateRequest $request
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
                trans('validations/api/admin/csau/platform/update.result.error.find')
            );
        }

        /**
         * Updating platform
         */
        $platform = $this->platformRepository->update(
            $platform,
            $request->input('name'),
            $request->input('voice_chat'),
            $request->input('visible_in_voice_chat'),
            $request->input('video_chat'),
            $request->input('visible_in_video_chat')
        );

        /**
         * Checking uploaded icon exists
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Deleting platforms icons
                 */
                $this->mediaMicroservice->deletePlatformIcons(
                    $platform
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }

            try {

                /**
                 * Uploading platform icon
                 */
                $this->mediaMicroservice->storePlatformIcon(
                    $platform,
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
            $this->transformItem($platform, new PlatformListTransformer(
                $this->platformIconRepository->getByPlatforms(
                    new Collection([$platform])
                )
            )), trans('validations/api/admin/csau/platform/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param AttachActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function attachActivities(
        int $id,
        AttachActivitiesRequest $request
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
                trans('validations/api/admin/csau/platform/attachActivities.result.error.find')
            );
        }

        /**
         * Getting activities
         */
        $activities = $this->activityRepository->getByIds(
            $request->input('activities_ids')
        );

        /**
         * Attaching activities to platform
         */
        $this->platformRepository->attachActivities(
            $platform,
            $activities->pluck('id')
                ->values()
                ->toArray()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/platform/attachActivities.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $activityId
     * @param DetachActivityRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function detachActivity(
        int $id,
        int $activityId,
        DetachActivityRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super right
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/detachActivity.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting platform
         */
        $platform = $this->platformRepository->findById($id);

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/detachActivity.result.error.find.device')
            );
        }

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $activityId
        );

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/detachActivity.result.error.find.activity')
            );
        }

        /**
         * Detaching activity from a platform
         */
        $this->platformRepository->detachActivity(
            $platform,
            $activity
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/platform/detachActivity.result.success')
        );
    }

    /**
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting platform
         */
        $platform = $this->platformRepository->findById($id);

        /**
         * Checking platform existence
         */
        if (!$platform) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/platform/destroy.result.error.find')
            );
        }

        /**
         * Checking platform already has vybes
         */
        if ($platform->vybes->count()) {
            return $this->respondWithErrors([
                'vybes' => trans('validations/api/admin/csau/platform/destroy.result.error.vybes')
            ]);
        }

        try {

            /**
             * Deleting platform icons
             */
            $this->mediaMicroservice->deletePlatformIcons(
                $platform
            );
        } catch (Exception $exception) {

            /**
             * Adding background error to controller stack
             */
            $this->addBackgroundError(
                $exception
            );
        }

        /**
         * Deleting platform
         */
        $this->platformRepository->delete(
            $platform
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/platform/destroy.result.success')
        );
    }
}
