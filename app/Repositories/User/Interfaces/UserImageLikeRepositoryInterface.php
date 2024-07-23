<?php

namespace App\Repositories\User\Interfaces;

use App\Models\MongoDb\User\Media\UserImageLike;
use App\Models\MySql\User\User;

/**
 * Interface UserImageLikeRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserImageLikeRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return UserImageLike|null
     */
    public function findById(
        ?string $id
    ) : ?UserImageLike;

    /**
     * This method provides counting rows
     * with an eloquent model by certain query
     *
     * @param int $imageId
     *
     * @return int
     */
    public function getLikesForImageCount(
        int $imageId
    ) : int;

    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     * @param int $imageId
     *
     * @return bool
     */
    public function existsForImage(
        User $user,
        int $imageId
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param int $imageId
     *
     * @return UserImageLike|null
     */
    public function store(
        User $user,
        int $imageId
    ) : ?UserImageLike;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int $imageId
     *
     * @return bool
     */
    public function deleteForImage(
        User $user,
        int $imageId
    ) : bool;
}
