<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Media\AdminAvatar;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface AdminAvatarRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface AdminAvatarRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AdminAvatar|null
     */
    public function findById(
        ?int $id
    ) : ?AdminAvatar;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param Admin $admin
     *
     * @return AdminAvatar|null
     */
    public function findByAdmin(
        Admin $admin
    ) : ?AdminAvatar;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Collection $admins
     *
     * @return Collection
     */
    public function getByAdmins(
        Collection $admins
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
