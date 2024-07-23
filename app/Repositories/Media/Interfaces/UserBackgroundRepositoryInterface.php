<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Media\UserBackground;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserBackgroundRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface UserBackgroundRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return UserBackground|null
     */
    public function findById(
        ?int $id
    ) : ?UserBackground;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param User $user
     *
     * @return UserBackground|null
     */
    public function findByUser(
        User $user
    ) : ?UserBackground;

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
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByUsers(
        Collection $users
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Collection $userProfileRequests
     *
     * @return Collection
     */
    public function getByRequests(
        Collection $userProfileRequests
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
