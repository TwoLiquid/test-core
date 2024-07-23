<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\Media\UserIdVerificationImage;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserIdVerificationImageRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface UserIdVerificationImageRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return UserIdVerificationImage|null
     */
    public function findById(
        ?int $id
    ) : ?UserIdVerificationImage;

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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getByUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Collection
     */
    public function getByRequest(
        UserIdVerificationRequest $userIdVerificationRequest
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Collection $userIdVerificationRequests
     *
     * @return Collection
     */
    public function getByRequests(
        Collection $userIdVerificationRequests
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
