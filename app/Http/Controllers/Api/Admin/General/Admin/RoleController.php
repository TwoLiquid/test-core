<?php

namespace App\Http\Controllers\Api\Admin\General\Admin;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\General\Admin\Interfaces\RoleControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Admin\Role\IndexRequest;
use App\Http\Requests\Api\Admin\General\Admin\Role\StoreRequest;
use App\Http\Requests\Api\Admin\General\Admin\Role\UpdateRequest;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Media\AdminAvatarRepository;
use App\Repositories\Role\RoleRepository;
use App\Services\Admin\AdminService;
use App\Services\Permission\PermissionService;
use App\Transformers\Api\Admin\General\Admin\Role\RoleFormTransformer;
use App\Transformers\Api\Admin\General\Admin\Role\RoleFullTransformer;
use App\Transformers\Api\Admin\General\Admin\Role\RoleListTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class RoleController
 *
 * @package App\Http\Controllers\Api\Admin\General\Admin
 */
final class RoleController extends BaseController implements RoleControllerInterface
{
    /**
     * @var AdminRepository
     */
    protected AdminRepository $adminRepository;

    /**
     * @var AdminAvatarRepository
     */
    protected AdminAvatarRepository $adminAvatarRepository;

    /**
     * @var AdminService
     */
    protected AdminService $adminService;

    /**
     * @var PermissionService
     */
    protected PermissionService $permissionService;

    /**
     * @var RoleRepository
     */
    protected RoleRepository $roleRepository;

    /**
     * RoleController constructor
     */
    public function __construct()
    {
        /** @var AdminRepository adminRepository */
        $this->adminRepository = new AdminRepository();

        /** @var AdminAvatarRepository adminAvatarRepository */
        $this->adminAvatarRepository = new AdminAvatarRepository();

        /** @var AdminService adminService */
        $this->adminService = new AdminService();

        /** @var PermissionService permissionService */
        $this->permissionService = new PermissionService();

        /** @var RoleRepository roleRepository */
        $this->roleRepository = new RoleRepository();
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
        if ($request->input('paginated')) {

            /**
             * Getting roles with pagination
             */
            $roles = $this->roleRepository->getAllPaginated(
                $request->input('page')
            );

            return $this->setPagination($roles)->respondWithSuccess(
                $this->transformCollection($roles, new RoleListTransformer(
                    $this->adminAvatarRepository->getByAdmins(
                        $this->adminService->getByRoles(
                            new Collection($roles->items())
                        )
                    )
                )), trans('validations/api/admin/general/admin/role/index.result.success')
            );
        }

        /**
         * Getting roles
         */
        $roles = $this->roleRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($roles, new RoleListTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByRoles(
                        $roles
                    )
                )
            )), trans('validations/api/admin/general/admin/role/index.result.success')
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
         * Getting role
         */
        $role = $this->roleRepository->findById($id);

        /**
         * Checking role existence
         */
        if (!$role) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/role/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($role, new RoleFullTransformer($role)),
            trans('validations/api/admin/general/admin/role/show.result.success')
        );
    }

    /**
     * @return JsonResponse
     */
    public function getForm() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([], new RoleFormTransformer),
            trans('validations/api/admin/general/admin/role/getForm.result.success')
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
         * Creating role
         */
        $role = $this->roleRepository->store(
            $request->input('name')
        );

        /**
         * Checking role existence
         */
        if (!$role) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/role/update.result.error.find')
            );
        }

        /**
         * Checking admins ids existence
         */
        if ($request->input('admins_ids')) {

            /**
             * Attaching admins to a role
             */
            $this->roleRepository->attachAdmins(
                $role,
                $request->input('admins_ids')
            );
        }

        /**
         * Inserting role permissions
         */
        $this->permissionService->createFromData(
            $role,
            $request->input('departments')
        );

        return $this->respondWithSuccess(
            $this->transformItem($role, new RoleFullTransformer($role)),
            trans('validations/api/admin/general/admin/role/store.result.success')
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
        /**
         * Getting role
         */
        $role = $this->roleRepository->findById($id);

        /**
         * Checking role existence
         */
        if (!$role) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/role/update.result.error.find')
            );
        }

        /**
         * Checking admins ids existence
         */
        if ($request->input('admins_ids')) {

            /**
             * Attaching admins to a role
             */
            $this->roleRepository->attachAdmins(
                $role,
                $request->input('admins_ids')
            );
        }

        /**
         * Updating role permissions
         */
        $this->permissionService->updateFromData(
            $role,
            $request->input('departments')
        );

        return $this->respondWithSuccess(
            $this->transformItem($role, new RoleFullTransformer($role)),
            trans('validations/api/admin/general/admin/role/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting role
         */
        $role = $this->roleRepository->findById($id);

        /**
         * Checking role existence
         */
        if (!$role) {
            return $this->respondWithError(
                trans('validations/api/admin/general/admin/role/destroy.result.error.find')
            );
        }

        /**
         * Deleting role
         */
        $this->roleRepository->delete(
            $role
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/admin/role/destroy.result.success')
        );
    }
}
