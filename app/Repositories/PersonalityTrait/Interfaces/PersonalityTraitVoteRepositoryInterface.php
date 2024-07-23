<?php

namespace App\Repositories\PersonalityTrait\Interfaces;

use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\PersonalityTrait\PersonalityTraitVote;
use App\Models\MySql\User\User;

/**
 * Interface PersonalityTraitVoteRepositoryInterface
 *
 * @package App\Repositories\PersonalityTrait\Interfaces
 */
interface PersonalityTraitVoteRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return PersonalityTraitVote|null
     */
    public function findById(
        ?int $id
    ) : ?PersonalityTraitVote;

    /**
     * This method provides finding row existence
     * with an eloquent model by primary key
     *
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return bool
     */
    public function existsForVoter(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return PersonalityTraitVote|null
     */
    public function store(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : ?PersonalityTraitVote;

    /**
     * This method provides deleting existing row
     * with an eloquent model by certain query
     *
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return bool
     */
    public function deleteForVoter(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param PersonalityTraitVote $personalityTraitVote
     *
     * @return bool
     */
    public function delete(
        PersonalityTraitVote $personalityTraitVote
    ) : bool;
}
