<?php

namespace App\Http\Controllers\Api\Admin\Csau\ActivityTag;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\ActivityTag\Interfaces\ActivityTagControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\AttachActivitiesRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\DetachActivityRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\ActivityTag\UpdateRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Activity\ActivityTagRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Activity\ActivityTagService;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\ActivityTag\ActivityTagListTransformer;
use App\Transformers\Api\Admin\Csau\ActivityTag\ActivityTagTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class ActivityTagController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\ActivityTag
 */
final class ActivityTagController extends BaseController implements ActivityTagControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityTagRepository
     */
    protected ActivityTagRepository $activityTagRepository;

    /**
     * @var ActivityTagService
     */
    protected ActivityTagService $activityTagService;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * ActivityTagController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityTagRepository activityTagRepository */
        $this->activityTagRepository = new ActivityTagRepository();

        /** @var ActivityTagService activityTagService */
        $this->activityTagService = new ActivityTagService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var UserService userService */
        $this->userService = new UserService();
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
         * Checking paginated enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Checking search string existence
             */
            if ($request->input('search')) {
                
                /**
                 * Getting activity tags by search
                 */
                $activitiesTags = $this->activityTagRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting activity tags
                 */
                $activitiesTags = $this->activityTagRepository->getAllPaginated(
                    $request->input('page'),
                    $request->input('per_page')
                );
            }

            return $this->setPagination($activitiesTags)->respondWithSuccess(
                $this->transformCollection(
                    $activitiesTags,
                    new ActivityTagListTransformer(
                        $this->categoryIconRepository->getByCategories(
                            $this->activityTagService->getActivityTagsAllCategories(
                                new Collection($activitiesTags->items())
                            )
                        )
                    )
                ), trans('validations/api/admin/csau/activityTag/index.result.success')
            );
        }

        /**
         * Checking search string existence
         */
        if ($request->input('search')) {

            /**
             * Getting activity tags by search
             */
            $activitiesTags = $this->activityTagRepository->getAllBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting activity tags
             */
            $activitiesTags = $this->activityTagRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection(
                $activitiesTags,
                new ActivityTagListTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            $activitiesTags
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/index.result.success')
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
         * Getting activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById($id);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            new Collection([$activityTag])
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/show.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     * 
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Getting subcategory if exists
         */
        $subcategory = null;
        if ($request->input('subcategory_id')) {
            $subcategory = $this->categoryRepository->findById(
                $request->input('subcategory_id')
            );
        }

        /**
         * Creating activity tag
         */
        $activityTag = $this->activityTagRepository->store(
            $category,
            $subcategory,
            $request->input('name'),
            $request->input('visible_in_category'),
            $request->input('visible_in_subcategory')
        );

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/store.result.error.create')
            );
        }

        /**
         * Getting related activities
         */
        $activities = $this->activityRepository->getByIds(
            $request->input('activities_ids')
        );

        /**
         * Checking activities relation is correct
         */
        if (!$this->activityTagService->checkRelatedActivities(
            $activityTag,
            $activities
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/store.result.error.activities')
            );
        }

        /**
         * Attaching activities
         */
        $this->activityTagRepository->attachActivities(
            $activityTag,
            $request->input('activities_ids')
        );

        /**
         * Getting full activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById(
            $activityTag->id
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            new Collection([$activityTag])
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        $activityTag = $this->activityTagRepository->findById($id);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/update.result.error.find')
            );
        }

        /**
         * Creating activity tag
         */
        $activityTag = $this->activityTagRepository->update(
            $activityTag,
            null,
            null,
            $request->input('name'),
            $request->input('visible_in_category'),
            $request->input('visible_in_subcategory')
        );

        /**
         * Getting full activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById(
            $activityTag->id
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            new Collection([$activityTag])
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/update.result.success')
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
         * Getting activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById($id);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/attachActivities.result.error.find')
            );
        }

        /**
         * Getting related activities
         */
        $activities = $this->activityRepository->getByIds(
            $request->input('activities_ids')
        );

        /**
         * Checking activities relation
         */
        if (!$this->activityTagService->checkRelatedActivities(
            $activityTag,
            $activities
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/attachActivities.result.error.activities')
            );
        }

        /**
         * Attaching activities to activity tag
         */
        $this->activityTagRepository->attachActivities(
            $activityTag,
            $request->input('activities_ids'),
            true
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            new Collection([$activityTag])
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/attachActivities.result.success')
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
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById($id);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/detachActivity.result.error.find.activityTag')
            );
        }

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById($activityId);

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/detachActivity.result.error.find.activity')
            );
        }

        /**
         * Detaching activity to activity tag
         */
        $this->activityTagRepository->detachActivity(
            $activityTag,
            $activity
        );

        /**
         * Getting full activity tag
         */
        $activityTag = $this->activityTagRepository->findFullForAdminById(
            $activityTag->id
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $activityTag,
                new ActivityTagTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->activityTagService->getActivityTagsAllCategories(
                            new Collection([$activityTag])
                        )
                    )
                )
            ), trans('validations/api/admin/csau/activityTag/detachActivity.result.success')
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
                trans('validations/api/admin/csau/activityTag/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting activity tag
         */
        $activityTag = $this->activityTagRepository->findById($id);

        /**
         * Checking activity tag existence
         */
        if (!$activityTag) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/activityTag/destroy.result.error.find')
            );
        }

        /**
         * Deleting activity tag
         */
        $this->activityTagRepository->delete(
            $activityTag
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/activityTag/destroy.result.success')
        );
    }
}
