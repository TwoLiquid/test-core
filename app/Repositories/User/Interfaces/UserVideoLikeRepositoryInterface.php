<?php

namespace App\Repositories\User\Interfaces;

use App\Models\MongoDb\User\Media\UserVideoLike;
use App\Models\MySql\User\User;

/**
 * Interface UserVideoLikeRepositoryInterface
 *
 * @package App\Repositories\User\Interfaces
 */
interface UserVideoLikeRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return UserVideoLike|null
     */
    public function findById(
        ?string $id
    ) : ?UserVideoLike;

    /**
     * This method provides counting rows
     * with an eloquent model by certain query
     *
     * @param int $videoId
     *
     * @return int
     */
    public function getLikesForVideoCount(
        int $videoId
    ) : int;

    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     * @param int $videoId
     *
     * @return bool
     */
    public function existsForVideo(
        User $user,
        int $videoId
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param int $videoId
     *
     * @return UserVideoLike|null
     */
    public function store(
        User $user,
        int $videoId
    ) : ?UserVideoLike;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     * @param int $videoId
     *
     * @return bool
     */
    public function deleteForVideo(
        User $user,
        int $videoId
    ) : bool;
}
