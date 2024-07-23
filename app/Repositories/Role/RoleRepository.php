<?php

namespace App\Repositories\Role;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Role;
use App\Repositories\BaseRepository;
use App\Repositories\Role\Interfaces\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class RoleRepository
 *
 * @package App\Repositories\Role
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * RoleRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.role.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Role|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Role
    {
        try {
            return Role::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Role|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Role
    {
        try {
            return Role::query()
                ->with([
                    'admins',
                    'permissions'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
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
            return Role::query()
                ->with([
                    'admins'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
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
            return Role::query()
                ->with([
                    'admins'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return Role|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $name
    ) : ?Role
    {
        try {
            return Role::query()->create([
                'name' => trim($name)
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param string|null $name
     *
     * @return Role
     *
     * @throws DatabaseException
     */
    public function update(
        Role $role,
        ?string $name
    ) : Role
    {
        try {
            $role->update([
                'name' => $name ? trim($name) : $role->name
            ]);

            return $role;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param Admin $admin
     *
     * @throws DatabaseException
     */
    public function attachAdmin(
        Role $role,
        Admin $admin
    ) : void
    {
        try {
            $role->admins()->sync([
                $admin->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param array $adminsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachAdmins(
        Role $role,
        array $adminsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $role->admins()->sync(
                $adminsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param Admin $admin
     *
     * @throws DatabaseException
     */
    public function detachAdmin(
        Role $role,
        Admin $admin
    ) : void
    {
        try {
            $role->admins()->detach([
                $admin->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     * @param array $adminsIds
     *
     * @throws DatabaseException
     */
    public function detachAdmins(
        Role $role,
        array $adminsIds
    ) : void
    {
        try {
            $role->admins()->detach(
                $adminsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Role $role
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Role $role
    ) : bool
    {
        try {
            return $role->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/role.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}