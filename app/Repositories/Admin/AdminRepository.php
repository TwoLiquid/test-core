<?php

namespace App\Repositories\Admin;

use App\Exceptions\DatabaseException;
use App\Lists\Admin\Status\AdminStatusListItem;
use App\Models\MySql\Admin\Admin;
use App\Repositories\Admin\Interfaces\AdminRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class AdminRepository
 *
 * @package App\Repositories\Admin
 */
class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    /**
     * AdminRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.admin.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Admin|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Admin
    {
        try {
            return Admin::query()
                ->with([
                    'authProtection'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Admin|null
     *
     * @throws DatabaseException
     */
    public function findByAuthId(
        ?int $id
    ) : ?Admin
    {
        try {
            return Admin::query()
                ->with([
                    'authProtection'
                ])
                ->where('auth_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $email
     *
     * @return Admin|null
     *
     * @throws DatabaseException
     */
    public function findByEmail(
        string $email
    ) : ?Admin
    {
        try {
            return Admin::query()
                ->with([
                    'authProtection'
                ])
                ->where('email', '=', $email)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     * @param string $email
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function findByEmailExceptAdmin(
        Admin $admin,
        string $email
    ) : bool
    {
        try {
            return Admin::query()
                ->where('id', '!=', $admin->id)
                ->where('email', '=', $email)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
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
            return Admin::query()
                ->with([
                    'authProtection'
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
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
            return Admin::query()
                ->with([
                    'authProtection'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param array|null $rolesIds
     * @param int|null $statusId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?string $name = null,
        ?string $email = null,
        ?array $rolesIds = null,
        ?int $statusId = null
    ) : Collection
    {
        try {
            return Admin::query()
                ->with([
                    'authProtection' => function ($query) {
                        $query->select([
                            'id',
                            'added_at'
                        ]);
                    }
                ])
                ->when($name, function ($query) use ($name) {
                    $query->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                })
                ->when($email, function ($query) use ($email) {
                    $query->where('email', '=', $email);
                })
                ->when($rolesIds, function ($query) use ($rolesIds) {
                    $query->whereHas('roles', function($query) use ($rolesIds) {
                        $query->whereIn('admin_role.role_id', $rolesIds);
                    });
                })
                ->when($statusId, function ($query) use ($statusId) {
                    $query->where('status_id', '=', $statusId);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $name
     * @param string|null $email
     * @param array|null $rolesIds
     * @param int|null $statusId
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?string $name = null,
        ?string $email = null,
        ?array $rolesIds = null,
        ?int $statusId = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Admin::query()
                ->with([
                    'authProtection' => function ($query) {
                        $query->select([
                            'id',
                            'added_at'
                        ]);
                    }
                ])
                ->when($name, function ($query) use ($name) {
                    $query->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                })
                ->when($email, function ($query) use ($email) {
                    $query->where('email', '=', $email);
                })
                ->when($rolesIds, function ($query) use ($rolesIds) {
                    $query->whereHas('roles', function($query) use ($rolesIds) {
                        $query->whereIn('admin_role.role_id', $rolesIds);
                    });
                })
                ->when($statusId, function ($query) use ($statusId) {
                    $query->where('status_id', '=', $statusId);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $adminsIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $adminsIds
    ) : Collection
    {
        try {
            return Admin::query()
                ->whereIn('id', $adminsIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int $authId
     * @param AdminStatusListItem $adminStatusListItem
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param bool $fullAccess
     *
     * @return Admin|null
     *
     * @throws DatabaseException
     */
    public function store(
        int $authId,
        AdminStatusListItem $adminStatusListItem,
        string $firstName,
        string $lastName,
        string $email,
        bool $fullAccess
    ) : ?Admin
    {
        try {
            return Admin::query()->create([
                'auth_id'     => $authId,
                'status_id'   => $adminStatusListItem->id,
                'first_name'  => trim($firstName),
                'last_name'   => trim($lastName),
                'email'       => $email,
                'full_access' => $fullAccess
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     * @param int|null $authId
     * @param AdminStatusListItem|null $adminStatusListItem
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     * @param bool|null $fullAccess
     *
     * @return Admin
     *
     * @throws DatabaseException
     */
    public function update(
        Admin $admin,
        ?int $authId,
        ?AdminStatusListItem $adminStatusListItem,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?bool $fullAccess
    ) : Admin
    {
        try {
            $admin->update([
                'auth_id'     => $authId ?: $admin->auth_id,
                'status_id'   => $adminStatusListItem ? $adminStatusListItem->id : $admin->status_id,
                'first_name'  => $firstName ? trim($firstName) : $admin->first_name,
                'last_name'   => $lastName ? trim($lastName) : $admin->last_name,
                'email'       => $email ? trim($email) : $admin->email,
                'full_access' => $fullAccess ?: $admin->full_access
            ]);

            return $admin;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     * @param AdminStatusListItem $adminStatusListItem
     *
     * @return Admin
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        Admin $admin,
        AdminStatusListItem $adminStatusListItem
    ) : Admin
    {
        try {
            $admin->update([
                'status_id' => $adminStatusListItem->id
            ]);

            return $admin;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     * @param bool $initialPassword
     *
     * @return Admin
     *
     * @throws DatabaseException
     */
    public function updateInitialPassword(
        Admin $admin,
        bool $initialPassword
    ) : Admin
    {
        try {
            $admin->update([
                'initial_password'     => $initialPassword,
                'password_reset_token' => null
            ]);

            return $admin;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return Admin
     *
     * @throws DatabaseException
     */
    public function setPasswordResetToken(
        Admin $admin
    ) : Admin
    {
        try {
            $admin->update([
                'password_reset_token' => md5(trim($admin->email) . time())
            ]);

            return $admin;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return Admin
     *
     * @throws DatabaseException
     */
    public function dropPasswordResetToken(
        Admin $admin
    ) : Admin
    {
        try {
            $admin->update([
                'password_reset_token' => null
            ]);

            return $admin;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Admin $admin
    ) : bool
    {
        try {
            return $admin->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/admin.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
