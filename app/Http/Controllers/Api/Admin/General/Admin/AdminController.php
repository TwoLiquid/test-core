<?php

namespace App\Http\Controllers\Api\Admin\General\Admin;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\General\Admin\Interfaces\AdminControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Admin\IndexRequest;
use App\Http\Requests\Api\Admin\General\Admin\ResetPasswordRequest;
use App\Http\Requests\Api\Admin\General\Admin\StoreRequest;
use App\Http\Requests\Api\Admin\General\Admin\UpdateRequest;
use App\Lists\Admin\Status\AdminStatusList;
use App\Microservices\Auth\AuthMicroservice;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Admin\AdminAuthProtectionRepository;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Media\AdminAvatarRepository;
use App\Services\File\MediaService;
use App\Transformers\Api\Admin\General\Admin\AdminFormTransformer;
use App\Transformers\Api\Admin\General\Admin\AdminPageTransformer;
use App\Transformers\Api\Admin\General\Admin\AdminTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class AdminController
 *
 * @package App\Http\Controllers\Api\Admin\General\Admin
 */
final class AdminController extends BaseController implements AdminControllerInterface
{
    /**
     * @var AdminAuthProtectionRepository
     */
    protected AdminAuthProtectionRepository $adminAuthProtectionRepository;

    /**
     * @var AdminRepository
     */
    protected AdminRepository $adminRepository;

    /**
     * @var AdminAvatarRepository
     */
    protected AdminAvatarRepository $adminAvatarRepository;

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
     * AdminController constructor
     */
    public function __construct()
    {
        /** @var AdminAuthProtectionRepository adminAuthProtectionRepository */
        $this->adminAuthProtectionRepository = new AdminAuthProtectionRepository();

        /** @var AdminRepository adminRepository */
        $this->adminRepository = new AdminRepository();

        /** @var AdminAvatarRepository adminAvatarRepository */
        $this->adminAvatarRepository = new AdminAvatarRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();
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
        if ($request->input('paginated')) {

            /**
             * Getting admins with pagination
             */
            $admins = $this->adminRepository->getAllPaginatedFiltered(
                $request->input('name'),
                $request->input('email'),
                $request->input('roles_ids'),
                $request->input('status_id'),
                $request->input('per_page'),
                $request->input('page')
            );

            return $this->setPagination($admins)->respondWithSuccess(
                $this->transformItem([], new AdminPageTransformer(
                    count($this->adminRepository->getAll()),
                    new Collection($admins->items()),
                    $this->adminAvatarRepository->getByAdmins(
                        new Collection($admins->items())
                    )
                ))['admin_page'],
                trans('validations/api/admin/general/admin/index.result.success')
            );
        }

        /**
         * Getting admins
         */
        $admins = $this->adminRepository->getAllFiltered(
            $request->input('name'),
            $request->input('email'),
            $request->input('roles_ids'),
            $request->input('status_id')
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new AdminPageTransformer(
                count($this->adminRepository->getAll()),
                $admins,
                $this->adminAvatarRepository->getByAdmins(
                    $admins
                )
            ))['admin_page'],
            trans('validations/api/admin/general/admin/index.result.success')
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
         * Getting admin
         */
        $admin = $this->adminRepository->findById($id);

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    new Collection([$admin])
                )
            )), trans('validations/api/admin/general/admin/show.result.success')
        );
    }

    /**
     * @return JsonResponse
     */
    public function getForm() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([], new AdminFormTransformer),
            trans('validations/api/admin/general/admin/getForm.result.success')
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
     * @throws MicroserviceException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Checking avatar existence
         */
        if ($request->input('avatar')) {
            $avatarFile = $request->input('avatar');

            /**
             * Validating admin avatar
             */
            $this->mediaService->validateUserAvatar(
                $avatarFile['content'],
                $avatarFile['mime'],
            );
        }

        /**
         * Generating random password
         */
        $generatedPassword = generateRandomString();

        /**
         * Creating admin in gateway
         */
        $userResponse = $this->authMicroservice->registerAdmin(
            $request->input('email'),
            $generatedPassword
        );

        /**
         * Getting admin status
         */
        $adminStatusListItem = AdminStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Creating admin
         */
        $admin = $this->adminRepository->store(
            $userResponse->id,
            $adminStatusListItem,
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('email'),
            false
        );

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/store.result.error.create')
            );
        }

        /**
         * Checking avatar existence
         */
        if ($request->input('avatar')) {

            try {

                /**
                 * Uploading admin avatar
                 */
                $this->mediaMicroservice->storeAdminAvatar(
                    $admin,
                    $request->input('avatar.content'),
                    $request->input('avatar.mime'),
                    $request->input('avatar.extension')
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
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/general/admin/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     * 
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findById($id);

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/update.result.error.find')
            );
        }

        /**
         * Checking avatar existence
         */
        if ($request->input('avatar')) {
            $avatarFile = $request->input('avatar');

            /**
             * Validating admin avatar
             */
            $this->mediaService->validateUserAvatar(
                $avatarFile['content'],
                $avatarFile['mime'],
            );
        }

        /**
         * Checking email existence
         */
        if ($this->adminRepository->findByEmailExceptAdmin(
            $admin,
            $request->input('email')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/update.result.error.email')
            );
        }

        /**
         * Updating gateway admin email
         */
        $this->authMicroservice->updateAdminEmail(
            $admin->email,
            $request->input('email')
        );

        /**
         * Getting admin status
         */
        $adminStatusListItem = AdminStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Updating admin
         */
        $admin = $this->adminRepository->update(
            $admin,
            null,
            $adminStatusListItem,
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('email'),
            false
        );

        /**
         * Checking avatar existence
         */
        if ($request->input('avatar')) {

            try {

                /**
                 * Deleting all admin avatars
                 */
                $this->mediaMicroservice->deleteAdminAvatars(
                    $admin
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
                 * Uploading admin avatar
                 */
                $this->mediaMicroservice->storeAdminAvatar(
                    $admin,
                    $request->input('avatar.content'),
                    $request->input('avatar.mime'),
                    $request->input('avatar.extension')
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
                $admin,
                new AdminTransformer(
                    $this->adminAvatarRepository->getByAdmins(
                        new Collection([$admin])
                    )
                )
            ), trans('validations/api/admin/general/admin/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function resetPassword(
        int $id,
        ResetPasswordRequest $request
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findById($id);

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/resetPassword.result.error.find')
            );
        }

        /**
         * Updating gateway password
         */
        $this->authMicroservice->updatePassword(
            $admin->email,
            $request->input('password')
        );

        return $this->respondWithSuccess(
            $this->transformItem($admin, new AdminTransformer),
            trans('validations/api/admin/general/admin/resetPassword.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function unlinkTwoFactor(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting admin
         */
        $admin = $this->adminRepository->findById($id);

        /**
         * Checking admin existence
         */
        if (!$admin) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/unlinkTwoFactor.result.error.find')
            );
        }

        /**
         * Deleting auth protection for admin
         */
        $this->adminAuthProtectionRepository->deleteForAdmin(
            $admin
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/admin/unlinkTwoFactor.result.success')
        );
    }
}
