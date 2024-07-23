<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Media\AdminAvatar;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\AdminAvatarRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class AdminAvatarRepository
 *
 * @package App\Repositories\Media
 */
class AdminAvatarRepository extends BaseRepository implements AdminAvatarRepositoryInterface
{
    /**
     * AdminAvatarRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.adminAvatar.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AdminAvatar|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AdminAvatar
    {
        try {
            return AdminAvatar::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return AdminAvatar|null
     *
     * @throws DatabaseException
     */
    public function findByAdmin(
        Admin $admin
    ) : ?AdminAvatar
    {
        try {
            return AdminAvatar::query()
                ->where('auth_id', '=', $admin->auth_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
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
            return AdminAvatar::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return AdminAvatar::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $admins
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByAdmins(
        Collection $admins
    ) : Collection
    {
        try {
            return AdminAvatar::query()
                ->whereIn('auth_id', $admins->pluck('auth_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return AdminAvatar::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/adminAvatar.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}