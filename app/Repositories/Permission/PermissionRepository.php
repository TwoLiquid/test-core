<?php

namespace App\Repositories\Permission;

use App\Exceptions\DatabaseException;
use App\Lists\Permission\PermissionListItem;
use App\Models\MySql\Permission;
use App\Models\MySql\Role;
use App\Repositories\BaseRepository;
use App\Repositories\Permission\Interfaces\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class PermissionRepository
 *
 * @package App\Repositories\Permission
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * PermissionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.permission.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Permission|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Permission
    {
        try {
            return Permission::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return Permission::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Permission::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForRole(
        Role $role
    ) : Collection
    {
        try {
            return Permission::query()
                ->where('role_id', '=', $role->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param PermissionListItem $permissionListItem
     * @param string $departmentCode
     * @param string $pageCode
     * @param bool $selected
     *
     * @return Permission|null
     *
     * @throws DatabaseException
     */
    public function store(
        Role $role,
        PermissionListItem $permissionListItem,
        string $departmentCode,
        string $pageCode,
        bool $selected
    ) : ?Permission
    {
        try {
            return Permission::query()->create([
                'role_id'         => $role->id,
                'permission_id'   => $permissionListItem->id,
                'department_code' => trim($departmentCode),
                'page_code'       => trim($pageCode),
                'selected'        => $selected
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Permission $permission
     * @param Role|null $role
     * @param PermissionListItem|null $permissionListItem
     * @param string|null $departmentCode
     * @param string|null $pageCode
     * @param bool|null $selected
     *
     * @return Permission
     *
     * @throws DatabaseException
     */
    public function update(
        Permission $permission,
        ?Role $role,
        ?PermissionListItem $permissionListItem,
        ?string $departmentCode,
        ?string $pageCode,
        ?bool $selected
    ) : Permission
    {
        try {
            $permission->update([
                'role_id'         => $role ? $role->id : $permission->role_id,
                'permission_id'   => $permissionListItem ? $permissionListItem->id : $permission->permission_id,
                'department_code' => $departmentCode ? trim($departmentCode) : $permission->department_code,
                'page_code'       => $pageCode ? trim($pageCode) : $permission->page_code,
                'selected'        => $selected ?: $permission->selected
            ]);

            return $permission;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param PermissionListItem $permissionListItem
     * @param string $departmentCode
     * @param string $pageCode
     * @param bool $selected
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function updateFromParameters(
        Role $role,
        PermissionListItem $permissionListItem,
        string $departmentCode,
        string $pageCode,
        bool $selected
    ) : bool
    {
        try {
            return Permission::query()
                ->where('role_id', '=', $role->id)
                ->where('permission_id', '=', $permissionListItem->id)
                ->where('department_code', '=', $departmentCode)
                ->where('page_code', '=', $pageCode)
                ->update([
                    'selected' => $selected
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $data
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function storeMany(
        array $data
    ) : bool
    {
        try {
            return Permission::query()->insert(
                $data
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Permission $permission
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Permission $permission
    ) : bool
    {
        try {
            return $permission->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/permission.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}