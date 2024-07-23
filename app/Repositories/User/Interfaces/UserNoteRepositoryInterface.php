<?php

namespace App\Repositories\User\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserNote;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserNoteRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserNoteRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return UserNote|null
     */
    public function findById(
        ?int $id
    ) : ?UserNote;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int $id
     *
     * @return UserNote|null
     */
    public function findForUserById(
        User $user,
        int $id
    ) : ?UserNote;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getForUserPaginated(
        User $user,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param Admin $admin
     * @param string $text
     *
     * @return UserNote|null
     */
    public function store(
        User $user,
        Admin $admin,
        string $text
    ) : ?UserNote;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserNote $userNote
     * @param User|null $user
     * @param Admin|null $admin
     * @param string|null $text
     *
     * @return UserNote
     */
    public function update(
        UserNote $userNote,
        ?User $user,
        ?Admin $admin,
        ?string $text
    ) : UserNote;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param UserNote $userNote
     * @param string $text
     *
     * @return UserNote
     */
    public function updateText(
        UserNote $userNote,
        string $text
    ) : UserNote;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param UserNote $userNote
     *
     * @return bool
     */
    public function delete(
        UserNote $userNote
    ) : bool;
}
