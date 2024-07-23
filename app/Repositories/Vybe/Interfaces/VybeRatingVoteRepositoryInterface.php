<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeRatingVote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface VybeRatingVoteRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeRatingVoteRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int $id
     *
     * @return VybeRatingVote|null
     */
    public function findById(
        int $id
    ) : ?VybeRatingVote;

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
     * This method provides checking row existence
     * with an eloquent model with pagination
     *
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function checkUserRatingVoteExistence(
        Vybe $vybe,
        User $user
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param User $user
     * @param int $rating
     *
     * @return VybeRatingVote|null
     */
    public function store(
        Vybe $vybe,
        User $user,
        int $rating
    ) : ?VybeRatingVote;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeRatingVote $vybeRatingVote
     * @param Vybe|null $vybe
     * @param User|null $user
     * @param int|null $rating
     *
     * @return VybeRatingVote
     */
    public function update(
        VybeRatingVote $vybeRatingVote,
        ?Vybe $vybe,
        ?User $user,
        ?int $rating
    ) : VybeRatingVote;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeRatingVote $vybeRatingVote
     *
     * @return bool
     */
    public function delete(
        VybeRatingVote $vybeRatingVote
    ) : bool;
}
