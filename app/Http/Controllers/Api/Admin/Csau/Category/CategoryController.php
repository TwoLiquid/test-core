<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Category\Interfaces\CategoryControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Category\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Category\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Category\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Category\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Category\UpdatePositionsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\UpdateRequest;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\Category\CategoryService;
use App\Services\File\MediaService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Category\CategoryListTransformer;
use App\Transformers\Api\Admin\Csau\Category\CategoryWithPaginationTransformer;
use App\Transformers\Api\Admin\Csau\Category\CategoryTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category
 */
final class CategoryController extends BaseController implements CategoryControllerInterface
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
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * CategoryController constructor
     */
    public function __construct()
    {
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var CategoryService categoryService */
        $this->categoryService = new CategoryService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();

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
         * Getting categories
         */
        $categories = $this->categoryRepository->getAllForAdmin();

        return $this->respondWithSuccess(
            $this->transformCollection(
                $categories,
                new CategoryListTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $categories
                    )
                )
            ), trans('validations/api/admin/csau/category/index.result.success')
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
         * Getting category
         */
        $category = $this->categoryRepository->findFullForAdminById($id);

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/show.result.error.find')
            );
        }

        /**
         * Parameter to paginate vybes under device
         */
        $perPage = $request->input('per_page') ?
            $request->input('per_page') :
            $this->categoryRepository->getPerPage();

        /**
         * Checking pagination is enabled
         */    
        if ($request->input('paginated') === true) {
            return $this->respondWithSuccess(
                $this->transformItem(
                    $category,
                    new CategoryWithPaginationTransformer(
                        $this->categoryIconRepository->getByCategories(
                            $this->categoryService->getCategoryWithSubcategories(
                                $category
                            )
                        ),
                        $this->activityImageRepository->getByActivities(
                            $this->activityService->getByCategory(
                                $category
                            )
                        ),
                        $perPage,
                        $request->input('page')
                    )
                ), trans('validations/api/admin/csau/category/show.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $this->categoryService->getCategoryWithSubcategories(
                            $category
                        )
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByCategory(
                            $category
                        )
                    )
                )
            ), trans('validations/api/admin/csau/category/show.result.success')
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
             * Validating category icon
             */
            $this->mediaService->validateCategoryIcon(
                $request->input('icon.content'),
                $request->input('icon.mime')
            );
        }

        /**
         * Creating category
         */
        $category = $this->categoryRepository->store(
            null,
            $request->input('name'),
            $request->input('visible')
        );

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/store.result.error.create')
            );
        }

        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Uploading category icon
                 */
                $this->mediaMicroservice->storeCategoryIcon(
                    $category,
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
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        new Collection([$category])
                    )
                )
            ), trans('validations/api/admin/csau/category/store.result.success')
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
         * Getting category
         */
        $category = $this->categoryRepository->findById($id);

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/update.result.error.find')
            );
        }

        /**
         * Updating category
         */
        $category = $this->categoryRepository->update(
            $category,
            null,
            $request->input('name'),
            $request->input('visible'),
            null
        );

        /**
         * Checking icon existence
         */
        if ($request->input('icon')) {

            try {

                /**
                 * Deleting category icons
                 */
                $this->mediaMicroservice->deleteCategoryIcons(
                    $category
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
                 * Uploading category icon
                 */
                $this->mediaMicroservice->storeCategoryIcon(
                    $category,
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
            $this->transformItem(
                $category,
                new CategoryTransformer(
                    $this->categoryIconRepository->getByCategories(
                        new Collection([$category])
                    )
                )
            ), trans('validations/api/admin/csau/category/update.result.success')
        );
    }

    /**
     * @param UpdatePositionsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        UpdatePositionsRequest $request
    ) : JsonResponse
    {
        /**
         * Updating categories positions
         */
        $categories = $this->categoryService->updatePositions(
            $request->input('categories_items')
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $categories,
                new CategoryListTransformer(
                    $this->categoryIconRepository->getByCategories(
                        $categories
                    )
                )
            ), trans('validations/api/admin/csau/category/updatePositions.result.success')
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
                trans('validations/api/admin/csau/category/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById($id);

        /**
         * Checking category existence
         */
        if (!$category) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/destroy.result.error.find')
            );
        }

        /**
         * Checking category already has vybes
         */
        if ($category->vybes->count()) {
            return $this->respondWithErrors([
                'vybes' => trans('validations/api/admin/csau/category/destroy.result.error.vybes')
            ]);
        }

        try {

            /**
             * Deleting category icons
             */
            $this->mediaMicroservice->deleteCategoryIcons(
                $category
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
         * Deleting category
         */
        $this->categoryRepository->delete(
            $category
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/destroy.result.success')
        );
    }
}
