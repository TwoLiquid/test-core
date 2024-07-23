<?php

namespace App\Repositories\Admin;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Admin\AdminAuthProtection;
use App\Repositories\Admin\Interfaces\AdminAuthProtectionRepositoryInterface;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class AdminAuthProtectionRepository
 *
 * @package App\Repositories\Admin
 */
class AdminAuthProtectionRepository extends BaseRepository implements AdminAuthProtectionRepositoryInterface
{
    /**
     * AdminAuthProtectionRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.adminAuthProtection.perPage');
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
    ) : ?AdminAuthProtection
    {
        try {
            return AdminAuthProtection::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return AdminAuthProtection|null
     *
     * @throws DatabaseException
     */
    public function findByAdmin(
        Admin $admin
    ) : ?AdminAuthProtection
    {
        try {
            return AdminAuthProtection::query()
                ->where('admin_id', '=', $admin->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
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
            return AdminAuthProtection::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
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
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     * @param string $secret
     *
     * @return AdminAuthProtection|null
     *
     * @throws DatabaseException
     */
    public function store(
        Admin $admin,
        string $secret
    ) : ?AdminAuthProtection
    {
        try {
            return AdminAuthProtection::query()->create([
                'admin_id' => $admin->id,
                'secret'   => $secret,
                'added_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AdminAuthProtection $adminAuthProtection
     * @param Admin|null $admin
     * @param string|null $secret
     *
     * @return AdminAuthProtection
     *
     * @throws DatabaseException
     */
    public function update(
        AdminAuthProtection $adminAuthProtection,
        ?Admin $admin,
        ?string $secret
    ) : AdminAuthProtection
    {
        try {
            $adminAuthProtection->update([
                'admin_id' => $adminAuthProtection->admin_id,
                'secret'   => $secret ? trim($secret) : $adminAuthProtection->secret
            ]);

            return $adminAuthProtection;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
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
    public function deleteForAdmin(
        Admin $admin
    ) : bool
    {
        try {
            return AdminAuthProtection::query()
                ->where('admin_id', '=', $admin->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AdminAuthProtection $adminAuthProtection
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        AdminAuthProtection $adminAuthProtection
    ) : bool
    {
        try {
            return $adminAuthProtection->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/admin/adminAuthProtection.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
